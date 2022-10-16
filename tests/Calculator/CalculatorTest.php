<?php

use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase {

	public function testGettersSetters(): void {
		$calculator = new Lib\Calculator\Calculator();

		$materials_store = new Lib\Materials\Store();
		$parts_list = new \Lib\Parts\PartsList();
		$scenarios_store = new \Lib\Scenarios\Store();

		$calculator->setMaterialsStore($materials_store);
		$calculator->setPartsList($parts_list);
		$calculator->setScenariosStore($scenarios_store);

		$this->assertInstanceOf(\Lib\Materials\Store::class, $calculator->getMaterialsStore());
		$this->assertInstanceOf(\Lib\Parts\PartsList::class, $calculator->getPartsList());
		$this->assertInstanceOf(\Lib\Scenarios\Store::class, $calculator->getScenariosStore());
	}

//	public function testRunCalcOnePartOneMaterial(): void {
//		$calculator = new Lib\Calculator\Calculator();
//
//		$parts_list = \Lib\Parts\PartsList::fromPost([]);
//		
//
//		$materials_store = new Lib\Materials\Store();
//		$scenarios_store = new \Lib\Scenarios\Store();
//
//		$calculator->setMaterialsStore($materials_store);
//		$calculator->setPartsList($parts_list);
//		$calculator->setScenariosStore($scenarios_store);
//
//		$this->assertNull($calculator->runCalc());
//	}

}
