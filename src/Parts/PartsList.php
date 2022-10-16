<?php

namespace Ooypunk\BeamsCalculatorLib\Parts;

use \OutOfRangeException;
use \Ooypunk\BeamsCalculatorLib\Parts\Part;

class PartsList implements \Countable {

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Parts\Part[]
	 */
	private $parts = [];

	/*
	 * General getters
	 */

	/**
	 * @return \Ooypunk\BeamsCalculatorLib\Parts\Part[]
	 */
	public function getParts(): array {
		return $this->parts;
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

}
