<?php

use PHPUnit\Framework\TestCase;

class NotEnoughMaterialTest extends TestCase {

	public function testInit(): void {
		$instance = new \Lib\Exceptions\NotEnoughMaterial();

		$group = new \Lib\Materials\Group();
		$instance->setGroup($group);
		$this->assertInstanceOf(\Lib\Materials\Group::class, $instance->getGroup());

		$this->expectException(\Lib\Exceptions\NotEnoughMaterial::class);
		throw $instance;
	}

}
