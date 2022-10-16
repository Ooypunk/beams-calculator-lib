<?php

use PHPUnit\Framework\TestCase;

class PartTest extends TestCase {

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Parts\Part
	 */
	private $part;

	protected function setUp(): void {
		parent::setUp();

		$length = 123;
		$width = 20;
		$height = 30;
		$label = 'Testpart 1';
		$this->part = new Ooypunk\BeamsCalculatorLib\Parts\Part($length, $width, $height, $label);
	}

	public function testCannotEmptyInit(): void {
		$this->expectException(ArgumentCountError::class);
		$part = new \Ooypunk\BeamsCalculatorLib\Parts\Part();
		$this->assertInstanceOf(\Ooypunk\BeamsCalculatorLib\Parts\Part::class, $part);
	}

	public function testCanHaveLabel(): void {
		$expected = 'Testpart 1';
		$this->assertEquals($expected, $this->part->getLabel());
	}

	public function testCanHaveLabelWithDimensions(): void {
		$expected = 'Testpart 1 [20x30x123]';
		$this->assertEquals($expected, $this->part->getLabelWithDims());
		$this->part->setNumber(2);
		$expected2 = 'Testpart 1 (2) [20x30x123]';
		$this->assertEquals($expected2, $this->part->getLabelWithDims());
	}

	/*
	 * Test functions in Base\Part
	 */

	public function testPartLength() {
		$this->assertEquals(123, $this->part->getLength());
		$this->part->setLength(1234);
		$this->assertEquals(1234, $this->part->getLength());
	}

	public function testPartWidth() {
		$this->assertEquals(20, $this->part->getWidth());
		$this->part->setWidth(203);
		$this->assertEquals(203, $this->part->getWidth());
	}

	public function testPartHeight() {
		$this->assertEquals(30, $this->part->getHeight());
		$this->part->setHeight(304);
		$this->assertEquals(304, $this->part->getHeight());
	}

	public function testPartLabel() {
		$this->assertEquals('Testpart 1', $this->part->getLabel());
		$this->part->setLabel('Altered label');
		$this->assertEquals('Altered label', $this->part->getLabel());
		$this->part->setNumber(2);
		$this->assertEquals('Altered label (2)', $this->part->getLabel());
	}

	public function testPartEmptyLabel() {
		$this->assertEquals('Testpart 1', $this->part->getLabel());
		$this->part->setLabel('');
		$this->assertEquals('Part [20x30x123]', $this->part->getLabel());
		$this->part->setNumber(2);
		$this->assertEquals('Part [20x30x123]', $this->part->getLabel());
	}

}
