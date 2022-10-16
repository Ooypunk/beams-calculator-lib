<?php

use PHPUnit\Framework\TestCase;

class PartsListTest extends TestCase {

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
		$instance = new \Lib\Parts\PartsList();
		$this->assertInstanceOf(\Lib\Parts\PartsList::class, $instance);
	}

	public function testFromPost(): void {
		$instance = \Lib\Parts\PartsList::fromPost($this->post);
		$this->assertInstanceOf(\Lib\Parts\PartsList::class, $instance);
		$this->assertCount(2, $instance);
	}

	public function testGetEmptyChild(): void {
		$child = \Lib\Parts\PartsList::getEmptyChild();
		$this->assertInstanceOf(Lib\Parts\Part::class, $child);
	}

	public function testChildFromArray(): void {
		$array = [
			'width' => '20',
			'height' => '30',
			'length' => '40',
			'label' => 'Test_20_30_40',
		];
		$child = \Lib\Parts\PartsList::childFromArray($array);
		$this->assertInstanceOf(Lib\Parts\Part::class, $child);
		$this->assertEquals('Test_20_30_40', $child->getLabel());
	}

	public function testFromCsvFile(): void {
		$filename = __DIR__ . '/parts.csv';
		$instance = \Lib\Parts\PartsList::fromCsvFile($filename);
		$this->assertInstanceOf(\Lib\Parts\PartsList::class, $instance);
		$this->assertCount(2, $instance);
	}

	public function testGetParts(): void {
		$instance = \Lib\Parts\PartsList::fromPost($this->post);
		$this->assertInstanceOf(\Lib\Parts\PartsList::class, $instance);
		$parts = $instance->getParts();
		$this->assertIsArray($parts);
		$this->assertCount(2, $parts);
	}

}
