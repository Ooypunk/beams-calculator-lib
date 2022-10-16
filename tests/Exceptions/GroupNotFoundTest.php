<?php

use PHPUnit\Framework\TestCase;

class GroupNotFoundTest extends TestCase {

	public function testInit(): void {
		$this->expectException(\Ooypunk\BeamsCalculatorLib\Exceptions\GroupNotFound::class);
		throw new \Ooypunk\BeamsCalculatorLib\Exceptions\GroupNotFound();
	}

}
