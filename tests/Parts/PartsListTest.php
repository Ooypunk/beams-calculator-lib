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

}
