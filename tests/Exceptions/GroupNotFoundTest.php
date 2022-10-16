<?php

use PHPUnit\Framework\TestCase;

class GroupNotFoundTest extends TestCase {

	public function testInit(): void {
		$this->expectException(\Lib\Exceptions\GroupNotFound::class);
		throw new \Lib\Exceptions\GroupNotFound();
	}

}
