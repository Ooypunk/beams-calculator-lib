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

	/*
	 * To/from array
	 */

	/**
	 * Create array from this materials store
	 * @return array
	 */
	public function toArray(): array {
		$array = [
			'label' => $this->label,
			'materials' => [],
		];
		foreach ($this->getMaterials() as $material) {
			$array['materials'][] = $material->toArray();
		}
		return $array;
	}

	/**
	 * Fill this materials list with given array
	 * @param array $array
	 * @return void
	 */
	public function fromArray(array $array): void {
		$label = isset($array['label']) ? $array['label'] : null;
		$this->setLabel($label);

		$materials = [];
		if (isset($array['materials']) && is_array($array['materials'])) {
			foreach ($array['materials'] as $material_arr) {
				$length = $material_arr['length'];
				$width = $material_arr['width'];
				$height = $material_arr['height'];
				$label = $material_arr['label'];
				$material = new Material($length, $width, $height, $label);
				if (isset($material_arr['number'])) {
					$material->setNumber($material_arr['number']);
				}
				$materials[] = $material;
			}
		}
		$this->setMaterials($materials);
	}

}
