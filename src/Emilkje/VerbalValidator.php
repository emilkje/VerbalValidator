<?php

namespace Emilkje;

use Emilkje\VerbalValidator\ChainableInterface;
use MarkWilson\VerbalExpression;
use MarkWilson\VerbalExpression\Matcher;

class ValidatorNotFoundException extends \ErrorException {};

class VerbalValidator implements ChainableInterface {

	private $tests;
	private $matcher;

	public function __construct(VerbalExpression\MatcherInterface $matcher) {
		$this->tests = array();
		$this->matcher = $matcher;
	}

	public function __call($name, array $arguments) {
		$method = "validate_{$name}";
			if (method_exists($this, $method)) {
				$expression = call_user_func_array(array($this, $method), $arguments);
				$this->addTest($arguments[0], $expression);
				return $this;
			} else if(method_exists($this, $name)) {
				return call_user_func_array(array($this, $name), $arguments);
			} else {
				throw new ValidatorNotFoundException("Validator " . $name . " in not implemented");
			}
	}

	private function getEngine() {
		return new VerbalExpression();
	}

	public function validate_url($url) {
		$ve = $this->getEngine();
		$ve->startOfLine()
						->then('http')
						->maybe('s')
						->then('://')
						->maybe('www.')
						->anythingBut(' ')
						->endOfLine();
		return $ve;
	}

	public function validate_name($str) {
		$ve = $this->getEngine();
		$ve->startOfLine()
						->anythingBut("1234567890")
						->maybe(" ")
						->anythingBut("1234567890")
						->endOfLine();

		return $ve;
	}

	public function test() {
		foreach ($this->tests as $test) {
			if (!$this->matcher->isMatch($test["expression"], $test["subject"])) {
				$this->tests = array();
				return false;
			}
		}
		$this->tests = array();
		return true;
	}

	public function addTest($subject, $expression) {
		$test = array("subject" => $subject, "expression" => $expression);
		array_push($this->tests, $test);
	}

	public function getTests() {
		return $this->tests;
	}

}
