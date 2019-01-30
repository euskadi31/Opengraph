<?php

use PHPUnit\Framework\TestCase,
	Opengraph\Opengraph;

class OpengraphTest extends TestCase
{
    public function testClass()
    {
        $og = new Opengraph();

        $this->assertInstanceOf('\Opengraph\Opengraph', $og);
        $this->assertInstanceOf('\Iterator', $og);
        $this->assertInstanceOf('\Serializable', $og);
        $this->assertInstanceOf('\Countable', $og);
    }
}