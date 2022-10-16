<?php

use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase {

	/**
	 * @var \Lib\Materials\Store();
	 */
	private $materials_store;

	/**
	 * @var \Lib\Parts\PartsList();
	 */
	private $parts_list;

	/**
	 * @var \Lib\Calculator\Calculator
	 */
	private $calculator;

	protected function setUp(): void {
		$this->materials_store = new Lib\Materials\Store();
		$this->parts_list = new \Lib\Parts\PartsList();
		$this->calculator = new Lib\Calculator\Calculator($this->materials_store, $this->parts_list);
	}

	public function testInitOk(): void {
		$this->assertInstanceOf(\Lib\Materials\Store::class, $this->calculator->getMaterialsStore());
		$this->assertInstanceOf(\Lib\Parts\PartsList::class, $this->calculator->getPartsList());
	}

	public function testCalcOneFitsInOne() {
		$part = new Lib\Parts\Part(100, 20, 30, 'Part1');
		$material = new Lib\Materials\Material(100, 20, 30, 'Material1');

		$this->parts_list->addPart($part);
		$this->materials_store->addMaterial($material);

		$this->calculator->runCalc();
		$scenarios = $this->calculator->getLeastWasteScenarios();
		$this->assertCount(1, $scenarios);
		$this->assertEquals(1, $this->calculator->getCalculationsCount());
	}

	public function testCalcOneNoFitInOne() {
		$part = new Lib\Parts\Part(110, 20, 30, 'Part1');
		$material = new Lib\Materials\Material(100, 20, 30, 'Material1');

		$this->parts_list->addPart($part);
		$this->materials_store->addMaterial($material);

		$this->calculator->runCalc();
		$scenarios = $this->calculator->getLeastWasteScenarios();
		$this->assertCount(0, $scenarios);
		$this->assertEquals(1, $this->calculator->getCalculationsCount());
	}

}
