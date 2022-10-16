<?php

use PHPUnit\Framework\TestCase;

class PartTooLargeTest extends TestCase {

	public function testInit(): void {
		$this->expectException(\Ooypunk\BeamsCalculatorLib\Exceptions\PartTooLarge::class);
		throw new \Ooypunk\BeamsCalculatorLib\Exceptions\PartTooLarge();
	}

}
