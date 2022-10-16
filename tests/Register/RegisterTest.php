<?php

use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase {

	public function testCanInit(): void {
		$register = \Ooypunk\BeamsCalculatorLib\Register\Register::getInstance();
		$this->assertInstanceOf(\Ooypunk\BeamsCalculatorLib\Register\Register::class, $register);
	}

	public function testMagicFunctionsWork(): void {
		$register = \Ooypunk\BeamsCalculatorLib\Register\Register::getInstance();
		$this->assertFalse(isset($register->testvar));
		$register->testvar = 'wef';
		$this->assertEquals($register->testvar, 'wef');
		$this->assertTrue(isset($register->testvar));

		$this->assertNull($register->unknown_var);
	}

}
