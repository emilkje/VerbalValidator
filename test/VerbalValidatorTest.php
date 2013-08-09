<?php

namespace Emilkje\Test;

require_once __DIR__ . '/../vendor/autoload.php';

use Emilkje\VerbalValidator;
use MarkWilson\VerbalExpression;
use MarkWilson\VerbalExpression\Matcher;

/**
 * Test VerbalValidator
 *
 * @author Mark Wilson <mark@89allport.co.uk>
 */
class VerbalValidatorTest extends \PHPUnit_Framework_TestCase {

	private $validate;

	protected function setUp() {
		$this->validate = new VerbalValidator(new Matcher());
	}

	public function testAddTest() {
		$instance = $this->validate;
		$this->assertTrue(is_array($instance->getTests()));
		
		$tests = $instance->getTests();
		$this->assertTrue(empty($tests));
		
		$instance->url("http://emilkjelsrud.com");
		$i = count($instance->getTests());
		$this->assertTrue($i == 1);
		
		$instance->url("http://github.com");
		$j = count($instance->getTests());
		$this->assertTrue($j == 2, "Expected 2 tests - found " . $j);
		
		$instance->test();
		$tests = $instance->getTests();
		$this->assertTrue(empty($tests), "Expected tests to be flushed after test execution");
		
	}
	
	public function testChainingPopulatesTests() {
		$count = count($this->validate->url('first test')->url('second test')->getTests());
		$this->assertTrue($count == 2);
		$this->validate->url('third test')->url('fourth test')->url('fifthTest');
		$count = count($this->validate->getTests());
		$this->assertTrue($count == 5);
	}
	
	/**
	 * Test string matching
	 *
	 * @return void
	 */
	public function testUrl() {
		$urls = array(
				"https://facebook.com/test/testing.php",
				"http://google.com",
				"http://www.emilkje.net"
		);
		$nonUrls = array(
				"11234",
				"htp://www.example.com",
				"http://goog le.com"
		);
		
		foreach($urls as $url) {
			$this->assertTrue($this->validate->url($url)->test(), "Expected " . $url . " to be valid url");
		}
		foreach($nonUrls as $url) {
			$this->assertTrue(!$this->validate->url($url)->test(), "Expected " . $url . " to be invalid url");
		}
		
		$this->assertTrue($this->validate->url('foo') instanceof VerbalValidator);
	}

	public function testName() {
		$this->assertTrue($this->validate->name("Emil Kjelsrud")->test());
		$this->assertTrue($this->validate->name("Daniel")->test());
		$this->assertTrue(!$this->validate->name("Emil0Kjelsrud")->test());
		$this->assertTrue(!$this->validate->name("emil09")->test());
		$this->assertTrue($this->validate->name('foo') instanceof VerbalValidator);
	}

}
