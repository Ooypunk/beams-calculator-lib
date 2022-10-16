<?php

use PHPUnit\Framework\TestCase;

class PartitionerTest extends TestCase {

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Partitions\Partitioner
	 */
	private $partitioner;

	protected function setUp(): void {
		$limiter = new \Ooypunk\BeamsCalculatorLib\Partitions\Limiter(2);
		$completer = new Ooypunk\BeamsCalculatorLib\Partitions\Completer(2);
		$this->partitioner = new Ooypunk\BeamsCalculatorLib\Partitions\Partitioner($limiter, $completer);
	}

	public function testCannotHaveNegativeLimit() {
		$this->expectException(\Exception::class);
		$this->partitioner->getPartitioned(-2);
	}

	public function testOnePart(): void {
		$partitions = $this->partitioner->getPartitioned(1);

		$expected = [
			[
				0 => 1,
				1 => 0,
			],
			[
				0 => 0,
				1 => 1,
			],
		];
		$this->assertEquals($expected, $partitions);
	}

	public function testTwoParts(): void {
		$partitions = $this->partitioner->getPartitioned(2);

		$expected = [
			0 => [
				0 => 2,
				1 => 0,
			],
			1 => [
				0 => 0,
				1 => 2,
			],
			2 => [
				0 => 1,
				1 => 1,
			],
		];
		$this->assertEquals($expected, $partitions);
	}

	public function testThreeParts(): void {
		$partitions = $this->partitioner->getPartitioned(3);

		$expected = [
			0 => [
				0 => 3,
				1 => 0,
			],
			1 => [
				0 => 0,
				1 => 3,
			],
			2 => [
				0 => 2,
				1 => 1,
			],
			3 => [
				0 => 1,
				1 => 2,
			],
		];
		$this->assertEquals($expected, $partitions);
	}

	public function testThreePartsThreeMaterialsNotIndexed(): void {
		$nr_of_parts = 3;
		$nr_of_matrs = 3;

		$limiter = new Ooypunk\BeamsCalculatorLib\Partitions\Limiter($nr_of_matrs);
		$completer = new Ooypunk\BeamsCalculatorLib\Partitions\Completer($nr_of_matrs);

		$partitioner = new \Ooypunk\BeamsCalculatorLib\Partitions\Partitioner($limiter, $completer);
		$partitions = $partitioner->getPartitioned($nr_of_parts);

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

		$limiter = new Ooypunk\BeamsCalculatorLib\Partitions\Limiter($nr_of_matrs);
		$completer = new Ooypunk\BeamsCalculatorLib\Partitions\Completer($nr_of_matrs);

		$partitioner = new \Ooypunk\BeamsCalculatorLib\Partitions\Partitioner($limiter, $completer);
		$partitions = $partitioner->getPartitioned($nr_of_parts);

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
