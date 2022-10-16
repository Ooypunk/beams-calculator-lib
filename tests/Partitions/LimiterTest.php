<?php

use PHPUnit\Framework\TestCase;

/**
 * Only here to test parts of Limiter that aren't tested in PartitionerTest
 */
class LimiterTest extends TestCase {

	public function testCannotHaveNegativeLimit() {
		$this->expectException(\Exception::class);
		new \Lib\Partitions\Limiter(-2);
	}

}
