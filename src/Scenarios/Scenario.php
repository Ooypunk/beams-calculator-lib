<?php

namespace Lib\Scenarios;

use Lib\Materials\UsedMaterial;
use Lib\Exceptions\NotEnoughMaterial;

/**
 * Scenario: list of used_materials (=material that is used in a scenario), with
 * parts in it. Materials and parts are divided according to given indexation.
 */
class Scenario {

	/**
	 * @var array|null
	 */
	private $indexed = null;

	/**
	 * @var \Lib\Materials\UsedMaterial[]
	 */
	private $used_materials = [];

	/**
	 * @var \Exception[]
	 */
	private $exceptions = [];

	/*
	 * General getters
	 */

	/**
	 * @return array|null "Indexed" array: array that indicates what materials
	 * should be used, and what parts should be in what material.
	 */
	public function getIndexed(): ?array {
		return $this->indexed;
	}

	public function getUsedMaterials(): array {
		return $this->used_materials;
	}

	public function getExceptions(): array {
		return $this->exceptions;
	}

	/*
	 * General setters
	 */

	public function setIndexed(array $indexed): void {
		$this->indexed = $indexed;
	}

	public function setUsedMaterials(array $used_materials): void {
		$this->used_materials = $used_materials;
	}

	/*
	 * "Adders"
	 */

	public function addUsedMaterial(UsedMaterial $used_material): void {
		$this->used_materials[] = $used_material;
	}

	public function addException(\Exception $exception): void {
		$this->exceptions[] = $exception;
	}

	/*
	 * Calculations
	 */

	public function assertPartsFitInMaterials(): void {
		foreach ($this->used_materials as $used_material) {
			$used_len = $used_material->getUsedLength();
			$total_len = $used_material->getTotalLength();
			if ($used_len > $total_len) {
				$msg = $used_len . ' gevraagd, ' . $total_len . ' beschikbaar';
				$msg .= ' van materiaal ' . $used_material->getMaterialLabel();
				$exception = new NotEnoughMaterial($msg);
				$this->addException($exception);
			}
		}
	}

	public function getWasteRatio(): float {
		$ratio = 0;
		foreach ($this->used_materials as $used_material) {
			$ratio += $used_material->getWasteRatio();
		}
		return $ratio;
	}

	/*
	 * Other
	 */

	public function hasExceptions(): bool {
		return count($this->exceptions) > 0;
	}

}
