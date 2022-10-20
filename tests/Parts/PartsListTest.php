<?php

use PHPUnit\Framework\TestCase;

class PartsListTest extends TestCase {

	public function testInitOk(): void {
		$instance = new \Ooypunk\BeamsCalculatorLib\Parts\PartsList();
		$this->assertInstanceOf(\Ooypunk\BeamsCalculatorLib\Parts\PartsList::class, $instance);
	}

	public function testGetParts() {
		$list = new Ooypunk\BeamsCalculatorLib\Parts\PartsList();
		$this->assertCount(0, $list->getParts());
	}

	public function testAddPart() {
		$list = new Ooypunk\BeamsCalculatorLib\Parts\PartsList();
		$part = new Ooypunk\BeamsCalculatorLib\Parts\Part(1, 2, 3, '4');
		$list->addPart($part);
		$this->assertCount(1, $list->getParts());
	}

	public function testGetByIndex() {
		$list = new Ooypunk\BeamsCalculatorLib\Parts\PartsList();

		$part4 = new Ooypunk\BeamsCalculatorLib\Parts\Part(1, 2, 3, 'Label 4');
		$list->addPart($part4);
		$part5 = new Ooypunk\BeamsCalculatorLib\Parts\Part(2, 3, 4, 'Label 5');
		$list->addPart($part5);
		$part6 = new Ooypunk\BeamsCalculatorLib\Parts\Part(3, 4, 5, 'Label 6');
		$list->addPart($part6);

		$found_part = $list->getByIndex(2);
		$expected = 'Label 5';
		$this->assertEquals($expected, $found_part->getLabel());
	}

	public function testGetByIndexException() {
		$list = new Ooypunk\BeamsCalculatorLib\Parts\PartsList();

		$this->expectException(\OutOfRangeException::class);
		$list->getByIndex(2);
	}

	public function testCount() {
		$list = new Ooypunk\BeamsCalculatorLib\Parts\PartsList();
		$part = new Ooypunk\BeamsCalculatorLib\Parts\Part(1, 2, 3, '4');
		$list->addPart($part);
		$this->assertEquals(1, $list->count());
	}

	public function testToArray() {
		$list = new Ooypunk\BeamsCalculatorLib\Parts\PartsList();
		$list->setLabel('Testlist 1');
		$part = new Ooypunk\BeamsCalculatorLib\Parts\Part(1, 2, 3, '4');
		$list->addPart($part);

		$array = $list->toArray();
		$expected = [
			'label' => 'Testlist 1',
			'parts' => [
				[
					'length' => 1,
					'width' => 2,
					'height' => 3,
					'label' => '4',
					'number' => 0,
				],
			],
		];
		$this->assertEquals($expected, $array);
	}

	public function testFromArray() {
		$list = new Ooypunk\BeamsCalculatorLib\Parts\PartsList();
		$input = [
			'label' => 'Testlist 1',
			'parts' => [
				[
					'length' => 1,
					'width' => 2,
					'height' => 3,
					'label' => 'Testpart 004',
					'number' => 0,
				],
			],
		];
		$list->fromArray($input);
		$this->assertEquals('Testlist 1', $list->getLabel());
		$this->assertCount(1, $list->getParts());
		$part = $list->getByIndex(1);
		$this->assertEquals('Testpart 004', $part->getLabel());
	}

}
