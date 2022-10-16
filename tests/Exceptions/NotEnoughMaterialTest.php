<?php

use PHPUnit\Framework\TestCase;

class NotEnoughMaterialTest extends TestCase {

	public function testInit(): void {
		$instance = new \Ooypunk\BeamsCalculatorLib\Exceptions\NotEnoughMaterial();

		$group = new \Ooypunk\BeamsCalculatorLib\Materials\Group();
		$instance->setGroup($group);
		$this->assertInstanceOf(\Ooypunk\BeamsCalculatorLib\Materials\Group::class, $instance->getGroup());

		$this->expectException(\Ooypunk\BeamsCalculatorLib\Exceptions\NotEnoughMaterial::class);
		throw $instance;
	}

}
