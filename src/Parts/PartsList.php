<?php

namespace Ooypunk\BeamsCalculatorLib\Parts;

use \OutOfRangeException;
use \Ooypunk\BeamsCalculatorLib\Parts\Part;

class PartsList implements \Countable {

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Parts\Part[]
	 */
	private $parts = [];

	/**
	 * @var string|null
	 */
	private $label;

	/*
	 * General getters
	 */

	/**
	 * @return \Ooypunk\BeamsCalculatorLib\Parts\Part[]
	 */
	public function getParts(): array {
		return $this->parts;
	}

	public function getLabel(): ?string {
		return $this->label;
	}

	/*
	 * General setters
	 */

	/**
	 * @param \Ooypunk\BeamsCalculatorLib\Parts\Part[] $parts
	 * @return void
	 */
	public function setParts(array $parts): void {
		$this->parts = $parts;
	}

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

	public function addPart(Part $part): void {
		$this->parts[] = $part;
	}

	/*
	 * Other getters
	 */

	public function getByIndex(int $index): Part {
		foreach ($this->parts as $key => $part) {
			if ((intval($key) + 1) === $index) {
				return $part;
			}
		}
		$msg = _('Part not found at index %d');
		throw new OutOfRangeException(sprintf($msg, $index));
	}

	/*
	 * \Countable
	 */

	public function count(): int {
		return count($this->parts);
	}

	/*
	 * To/from array
	 */

	/**
	 * Create array from this parts list
	 * @return array
	 */
	public function toArray(): array {
		$array = [
			'label' => $this->label,
			'parts' => [],
		];
		foreach ($this->getParts() as $part) {
			$array['parts'][] = $part->toArray();
		}
		return $array;
	}

	/**
	 * Fill this parts list with given array
	 * @param array $array
	 * @return void
	 */
	public function fromArray(array $array): void {
		$label = isset($array['label']) ? $array['label'] : null;
		$this->setLabel($label);

		$parts = [];
		if (isset($array['parts']) && is_array($array['parts'])) {
			foreach ($array['parts'] as $part_arr) {
				$length = $part_arr['length'];
				$width = $part_arr['width'];
				$height = $part_arr['height'];
				$label = $part_arr['label'];
				$part = new Part($length, $width, $height, $label);
				if (isset($part_arr['number'])) {
					$part->setNumber($part_arr['number']);
				}
				$parts[] = $part;
			}
		}
		$this->setParts($parts);
	}

}
