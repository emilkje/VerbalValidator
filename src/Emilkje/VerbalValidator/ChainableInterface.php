<?php

namespace Emilkje\VerbalValidator;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author emil
 */
interface ChainableInterface
{
    /**
     * Overload method calls.
     */
    public function __call($name, array $arguments);
}