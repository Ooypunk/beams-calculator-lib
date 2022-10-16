<?php

namespace Lib\Materials;

use Lib\Materials\GroupedStore;
use OutOfRangeException;

class Store implements \Countable {

	/**
	 * @var \Lib\Materials\Material[]
	 */
	private $materials = [];

	public function getMaterials(): array {
		return $this->materials;
	}

	public function addMaterial(Material $material): void {
		$this->materials[] = $material;
	}

	public function getGroupedStore(): GroupedStore {
		return new GroupedStore($this);
	}

	public function getByIndex(int $index): \Lib\Materials\Material {
		foreach ($this->materials as $key => $part) {
			if ((intval($key) + 1) === $index) {
				return $part;
			}
		}
		$msg = _('Material not found at index %d');
		throw new OutOfRangeException(sprintf($msg, $index));
	}

	public function count(): int {
		return count($this->materials);
	}

}
