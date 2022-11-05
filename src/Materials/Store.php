<?php

namespace Ooypunk\BeamsCalculatorLib\Materials;

use Ooypunk\BeamsCalculatorLib\Materials\Material;
use Ooypunk\BeamsCalculatorLib\Materials\GroupedStore;
use OutOfRangeException;

class Store implements \Countable {

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Materials\Material[]
	 */
	private $materials = [];

	/**
	 * @var string|null
	 */
	private $label;

	/*
	 * General getters
	 */

	public function getMaterials(): array {
		return $this->materials;
	}

	public function getLabel(): ?string {
		return $this->label;
	}

	/*
	 * General setters
	 */

	/**
	 * @param string|null $label List label
	 * @return void
	 */
	public function setLabel(?string $label): void {
		$this->label = $label;
	}

	/*
	 * "Adders"
	 */

	public function addMaterial(Material $material): void {
		$this->materials[] = $material;
	}

	/*
	 * Other
	 */

	public function getGroupedStore(): GroupedStore {
		return new GroupedStore($this);
	}

	public function getByIndex(int $index): Material {
		foreach ($this->materials as $key => $part) {
			if ((intval($key) + 1) === $index) {
				return $part;
			}
		}
		$msg = _('Material not found at index %d');
		throw new OutOfRangeException(sprintf($msg, $index));
	}

	/**
	 * @return int Number of materials in this store
	 */
	public function count(): int {
		return count($this->materials);
	}

}
