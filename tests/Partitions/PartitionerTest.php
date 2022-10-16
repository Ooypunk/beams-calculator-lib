<?php

use PHPUnit\Framework\TestCase;

class PartitionerTest extends TestCase {

	public function testCannotHaveNegativeLimit() {
		$this->expectException(\Exception::class);

		$partitioner = new Lib\Partitions\Partitioner();
		$partitioner->getPartitioned(-2);
	}

	public function testOnePart(): void {
		$partitioner = new Lib\Partitions\Partitioner();
		$partitions = $partitioner->getPartitioned(1);

		$expected = [
			[
				1,
			],
		];
		$this->assertEquals($expected, $partitions);
	}

	public function testTwoParts(): void {
		$partitioner = new Lib\Partitions\Partitioner();
		$partitions = $partitioner->getPartitioned(2);

		$expected = [
			[
				2,
			],
			[
				1,
				1,
			],
		];
		$this->assertEquals($expected, $partitions);
	}

	public function testThreeParts(): void {
		$partitioner = new Lib\Partitions\Partitioner();
		$partitions = $partitioner->getPartitioned(3);

		$expected = [
			[
				3,
			],
			[
				2,
				1,
			],
			[
				1,
				1,
				1,
			],
		];
		$this->assertEquals($expected, $partitions);
	}

	public function testThreePartsThreeMaterialsNotIndexed(): void {
		$nr_of_parts = 3;
		$nr_of_matrs = 3;

		$partitions = (new Lib\Partitions\Partitioner())
				->setLimiter(new Lib\Partitions\Limiter($nr_of_matrs))
				->setCompleter(new \Lib\Partitions\Completer($nr_of_matrs))
				->getPartitioned($nr_of_parts);

		$expected = [
			0 => [
				3,
				0,
				0,
			],
			1 => [
				0,
				3,
				0,
			],
			2 => [
				0,
				0,
				3,
			],
			3 => [
				2,
				1,
				0,
			],
			4 => [
				0,
				2,
				1,
			],
			5 => [
				1,
				0,
				2,
			],
			6 => [
				1,
				1,
				1,
			],
		];
		$this->assertEquals($expected, $partitions);
	}

	public function testFourPartsTwoMaterials(): void {
		$nr_of_parts = 4;
		$nr_of_matrs = 2;

		$partitions = (new Lib\Partitions\Partitioner())
				->setLimiter(new Lib\Partitions\Limiter($nr_of_matrs))
				->setCompleter(new \Lib\Partitions\Completer($nr_of_matrs))
				->getPartitioned($nr_of_parts);

		$expected = [
			0 => [
				4,
				0,
			],
			1 => [
				0,
				4,
			],
			2 => [
				3,
				1,
			],
			3 => [
				1,
				3,
			],
			4 => [
				2,
				2,
			],
		];
		$this->assertEquals($expected, $partitions);
	}

}
