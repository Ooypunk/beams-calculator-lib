<?php

use PHPUnit\Framework\TestCase;

class PartTooLargeTest extends TestCase {

	public function testInit(): void {
		$this->expectException(\Lib\Exceptions\PartTooLarge::class);
		throw new \Lib\Exceptions\PartTooLarge();
	}

}
