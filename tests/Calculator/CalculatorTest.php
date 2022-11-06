<?php

use PHPUnit\Framework\TestCase;
use Ooypunk\BeamsCalculatorLib\Calculator\Calculator;

class CalculatorTest extends TestCase {

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Materials\Store();
	 */
	private $materials_store;

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Parts\PartsList();
	 */
	private $parts_list;

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Calculator\Calculator
	 */
	private $calculator;

	protected function setUp(): void {
		$this->materials_store = new Ooypunk\BeamsCalculatorLib\Materials\Store();
		$this->parts_list = new \Ooypunk\BeamsCalculatorLib\Parts\PartsList();
		$this->calculator = new Calculator($this->materials_store, $this->parts_list);
	}

	public function testInitOk(): void {
		$this->assertInstanceOf(\Ooypunk\BeamsCalculatorLib\Materials\Store::class, $this->calculator->getMaterialsStore());
		$this->assertInstanceOf(\Ooypunk\BeamsCalculatorLib\Parts\PartsList::class, $this->calculator->getPartsList());
	}

	public function testCalcOneFitsInOne() {
		$part = new Ooypunk\BeamsCalculatorLib\Parts\Part(100, 20, 30, 'Part1');
		$material = new Ooypunk\BeamsCalculatorLib\Materials\Material(100, 20, 30, 'Material1');

		$this->parts_list->addPart($part);
		$this->materials_store->addMaterial($material);

		$this->calculator->runCalc();
		$scenarios = $this->calculator->getLeastWasteScenarios();
		$this->assertCount(1, $scenarios);
		$this->assertEquals(1, $this->calculator->getCalculationsCount());
	}

	public function testCalcOneNoFitInOne() {
		$part = new Ooypunk\BeamsCalculatorLib\Parts\Part(110, 20, 30, 'Part1');
		$material = new Ooypunk\BeamsCalculatorLib\Materials\Material(100, 20, 30, 'Material1');

		$this->parts_list->addPart($part);
		$this->materials_store->addMaterial($material);

		$this->calculator->runCalc();
		$scenarios = $this->calculator->getLeastWasteScenarios();
		$this->assertCount(0, $scenarios);
		$this->assertEquals(1, $this->calculator->getCalculationsCount());
	}

	public function testFromMiddleSizedCsvs() {
		$header_map = [
			'Lengte' => 'length',
			'Breedte' => 'width',
			'Dikte' => 'height',
			'Label' => 'label',
			'Aantal' => 'qty',
		];

		$parts_factory = new Ooypunk\BeamsCalculatorLib\Parts\PartsListFactory();
		$parts_file = __DIR__ . '/parts.csv';
		$parts_list = $parts_factory->fromCsvFile($parts_file, $header_map);

		$materials_factory = new Ooypunk\BeamsCalculatorLib\Materials\StoreFactory();
		$materials_file = __DIR__ . '/materials.csv';
		$materials_store = $materials_factory->fromCsvFile($materials_file, $header_map);

		$calculator = new Calculator($materials_store, $parts_list);
		$calculator->runCalc();
		$scenarios = $calculator->getLeastWasteScenarios();
		$this->assertCount(3, $scenarios);
		$this->assertEquals(5321, $calculator->getCalculationsCount());
	}

	public function testFromLargerSizedCsvs() {
		$header_map = [
			'Lengte' => 'length',
			'Breedte' => 'width',
			'Dikte' => 'height',
			'Label' => 'label',
			'Aantal' => 'qty',
		];

		$parts_factory = new Ooypunk\BeamsCalculatorLib\Parts\PartsListFactory();
		$parts_file = __DIR__ . '/parts_larger.csv';
		$parts_list = $parts_factory->fromCsvFile($parts_file, $header_map);

		$materials_factory = new Ooypunk\BeamsCalculatorLib\Materials\StoreFactory();
		$materials_file = __DIR__ . '/materials_larger.csv';
		$materials_store = $materials_factory->fromCsvFile($materials_file, $header_map);

		$calculator = new Calculator($materials_store, $parts_list);
		$calculator->runCalc();
		$scenarios = $calculator->getLeastWasteScenarios();
		$this->assertCount(3, $scenarios);
		$this->assertEquals(5321, $calculator->getCalculationsCount());
	}

}
