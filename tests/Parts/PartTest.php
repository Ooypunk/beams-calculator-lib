<?php

use PHPUnit\Framework\TestCase;

class PartTest extends TestCase {

	public function testCanEmptyInit(): void {
		$part = new \Lib\Parts\Part();
		$this->assertInstanceOf(\Lib\Parts\Part::class, $part);
	}

	public function testHasDefaultLabel(): void {
		$part = new \Lib\Parts\Part();
		$part->setWidth(11);
		$part->setHeight(12);
		$part->setLength(13);
		$expected = 'Part [11x12x13]';
		$this->assertEquals($expected, $part->getLabel());
	}

	public function testCanHaveLabel(): void {
		$part = new \Lib\Parts\Part();
		$part->setWidth(11);
		$part->setHeight(12);
		$part->setLength(13);
		$part->setLabel(14);
		$expected = '14';
		$this->assertEquals($expected, $part->getLabel());
	}

	public function testCanHaveLabelWithDimensions(): void {
		$part = new \Lib\Parts\Part();
		$part->setWidth(11);
		$part->setHeight(12);
		$part->setLength(13);
		$part->setLabel(15);
		$expected = '15 [11x12x13]';
		$this->assertEquals($expected, $part->getLabelWithDims());
	}

}
