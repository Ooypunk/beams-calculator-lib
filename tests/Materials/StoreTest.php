<?php

use PHPUnit\Framework\TestCase;

class StoreTest extends TestCase {

	private $post = [
		'width' => [
			20,
			30,
		],
		'height' => [
			40,
			50,
		],
		'length' => [
			60,
			70,
		],
		'label' => [
			'Test_w20_h40_l60',
			'Test_w30_h50_l70',
		],
	];

	public function testInitOk(): void {
		$instance = new \Lib\Materials\Store();
		$this->assertInstanceOf(\Lib\Materials\Store::class, $instance);
	}

	public function testGetMaterials() {
		$list = new Lib\Materials\Store();
		$this->assertCount(0, $list->getMaterials());
	}

	public function testAddMaterial() {
		$list = new Lib\Materials\Store();
		$part = new Lib\Materials\Material(1, 2, 3, '4');
		$list->addMaterial($part);
		$this->assertCount(1, $list->getMaterials());
	}

	public function testGetByIndex() {
		$list = new Lib\Materials\Store();

		$part4 = new Lib\Materials\Material(1, 2, 3, 'Label 4');
		$list->addMaterial($part4);
		$part5 = new Lib\Materials\Material(2, 3, 4, 'Label 5');
		$list->addMaterial($part5);
		$part6 = new Lib\Materials\Material(3, 4, 5, 'Label 6');
		$list->addMaterial($part6);

		$found_part = $list->getByIndex(2);
		$expected = 'Label 5';
		$this->assertEquals($expected, $found_part->getLabel());
	}

	public function testGetByIndexException() {
		$list = new Lib\Materials\Store();

		$this->expectException(\OutOfRangeException::class);
		$list->getByIndex(2);
	}

	public function testCount() {
		$list = new Lib\Materials\Store();
		$part = new Lib\Materials\Material(1, 2, 3, '4');
		$list->addMaterial($part);
		$this->assertEquals(1, $list->count());
	}

	public function testGetGroupedStore() {
		$list = new Lib\Materials\Store();
		$grouped_store = $list->getGroupedStore();
		$this->assertInstanceOf(\Lib\Materials\GroupedStore::class, $grouped_store);
	}

}
