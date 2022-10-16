<?php

namespace Lib\Parts;

use OutOfRangeException;

class PartsList implements \Countable {

	/**
	 * @var \Lib\Parts\Part[]
	 */
	private $parts = [];

	/*
	 * General getters
	 */

	/**
	 * @return \Lib\Parts\Part[]
	 */
	public function getParts(): array {
		return $this->parts;
	}

	/*
	 * "Adders"
	 */

	public function addPart(\Lib\Parts\Part $part): void {
		$this->parts[] = $part;
	}

	/*
	 * Other getters
	 */

	public function getByIndex(int $index): \Lib\Parts\Part {
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
