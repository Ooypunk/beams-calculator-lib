<?php

use PHPUnit\Framework\TestCase;

class PartsListFactoryTest extends TestCase {

	/**
	 * @var \Lib\Parts\PartsListFactory
	 */
	private $factory;

	/**
	 * @var array Mock POST array
	 */
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
		'qty' => [
			2,
			1,
		],
		'label' => [
			'Test_w20_h40_l60',
			'Test_w30_h50_l70',
		],
	];

	protected function setUp(): void {
		$this->factory = new Lib\Parts\PartsListFactory();
	}

	public function testInitOk() {
		$this->assertInstanceOf(\Lib\Parts\PartsListFactory::class, $this->factory);
	}

	public function testFromPost() {
		$list = $this->factory->fromPost($this->post);
		$this->assertInstanceOf(\Lib\Parts\PartsList::class, $list);
		$part = $list->getByIndex(2);
		$this->assertEquals('Test_w20_h40_l60 (2)', $part->getLabel());
	}

}
