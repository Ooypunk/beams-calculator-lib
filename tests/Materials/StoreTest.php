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

	public function testFromPost(): void {
		$instance = \Lib\Materials\Store::fromPost($this->post);
		$this->assertInstanceOf(\Lib\Materials\Store::class, $instance);
		$this->assertCount(2, $instance);
	}

	public function testGetEmptyChild(): void {
		$child = \Lib\Materials\Store::getEmptyChild();
		$this->assertInstanceOf(\Lib\Materials\Material::class, $child);
	}

	public function testChildFromArray(): void {
		$array = [
			'width' => '20',
			'height' => '30',
			'length' => '40',
			'label' => 'Test_w20_h30_l40',
		];
		$child = \Lib\Materials\Store::childFromArray($array);
		$this->assertInstanceOf(\Lib\Materials\Material::class, $child);
		$this->assertEquals('Test_w20_h30_l40', $child->getLabel());
	}

	public function testFromCsvFile(): void {
		$filename = __DIR__ . '/materials.csv';
		$instance = \Lib\Materials\Store::fromCsvFile($filename);
		$this->assertInstanceOf(\Lib\Materials\Store::class, $instance);
		$this->assertCount(4, $instance);
	}

	public function testGetGroupedStore(): void {
		$instance = \Lib\Materials\Store::fromPost($this->post);
		$this->assertInstanceOf(\Lib\Materials\Store::class, $instance);
		$grouped_store = $instance->getGroupedStore();
		$this->assertInstanceOf(\Lib\Materials\GroupedStore::class, $grouped_store);
	}

}
