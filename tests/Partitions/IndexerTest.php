<?php

use PHPUnit\Framework\TestCase;

class IndexerTest extends TestCase {

	public function testTwoPartsTwoMaterials(): void {
		$partitions = [
			[
				2,
				0
			],
			[
				0,
				2
			],
			[
				1,
				1
			],
		];

		$expected = [
			[
				[1, 2],
				[]
			],
			[
				[2, 1],
				[]
			],
			[
				[],
				[1, 2]
			],
			[
				[],
				[2, 1]
			],
			[
				[1],
				[2],
			],
			[
				[2],
				[1],
			],
		];

		$indexer = new \Ooypunk\BeamsCalculatorLib\Partitions\Indexer();
		$actual = $indexer->doIndexing($partitions, $expected);
		$this->assertEquals($expected, $actual);
	}

	public function testThreePartsThreeMaterials(): void {
		$partitions = [
			[
				3,
				0,
				0,
			],
			[
				0,
				3,
				0,
			],
			[
				0,
				0,
				3,
			],
			[
				2,
				1,
				0,
			],
			[
				0,
				2,
				1,
			],
			[
				1,
				0,
				2,
			],
			[
				1,
				1,
				1,
			],
		];

		$expected = [
			0 => [
				[1, 2, 3],
				[],
				[],
			],
			1 => [
				[3, 1, 2],
				[],
				[],
			],
			2 => [
				[2, 3, 1],
				[],
				[],
			],
			3 => [
				[],
				[1, 2, 3],
				[],
			],
			4 => [
				[],
				[3, 1, 2],
				[],
			],
			5 => [
				[],
				[2, 3, 1],
				[],
			],
			6 => [
				[],
				[],
				[1, 2, 3],
			],
			7 => [
				[],
				[],
				[3, 1, 2],
			],
			8 => [
				[],
				[],
				[2, 3, 1],
			],
			9 => [
				[1, 2],
				[3],
				[],
			],
			10 => [
				[3, 1],
				[2],
				[],
			],
			11 => [
				[2, 3],
				[1],
				[],
			],
			12 => [
				[],
				[1, 2],
				[3],
			],
			13 => [
				[],
				[3, 1],
				[2],
			],
			14 => [
				[],
				[2, 3],
				[1],
			],
			15 => [
				[1],
				[],
				[2, 3],
			],
			16 => [
				[3],
				[],
				[1, 2],
			],
			17 => [
				[2],
				[],
				[3, 1],
			],
			18 => [
				[1],
				[2],
				[3],
			],
			19 => [
				[3],
				[1],
				[2],
			],
			20 => [
				[2],
				[3],
				[1],
			],
		];

		$indexer = new \Ooypunk\BeamsCalculatorLib\Partitions\Indexer();
		$actual = $indexer->doIndexing($partitions, $expected);
		$this->assertEquals($expected, $actual);
	}

}
