<?php

namespace Ooypunk\BeamsCalculatorLib\Partitions;

use Ooypunk\BeamsCalculatorLib\Partitions\Partitioner;

/**
 * Helper class for Partitioner, doesn't work without it.
 * Complete the partitions, for example:
 * Input:
 * [3]
 * Expand (number of materials = 3):
 * [3,0,0]
 * Other possibilities: (=output)
 * [3,0,0],[0,3,0],[0,0,3]
 */
class Completer {

	/**
	 * @var int Minimum
	 */
	private $min = 1;

	public function __construct(int $min) {
		$this->min = $min;
	}

	public function run(Partitioner $parent): void {
		$collection = $parent->getCollection();

		foreach ($collection as $idx => $composition) {
			$filled = $this->getFilled($composition);
			$swapped = $this->getSwapped($filled);
			$collection[$idx] = $swapped;
		}

		$flattened = $this->flattenCollection($collection);
		$parent->setCollection($flattened);
	}

	private function getFilled(array $composition): array {
		$diff = $this->min - count($composition);
		if ($diff > 0) {
			for ($i = 0; $i < $diff; $i++) {
				$composition[] = 0;
			}
		}
		return $composition;
	}

	/**
	 * Helper function for run(): Partitioner generates compositions only once;
	 * this function generates the other possibilities for given composition and
	 * returns them.
	 * For example, when composition is 3-2-1, then output is:
	 * - 3-2-1
	 * - 1-3-2
	 * - 2-1-3
	 * @param array $composition
	 * @return array
	 */
	private function getSwapped(array $composition): array {
		$swapped = [
			$composition,
		];
		$max = count($composition) - 1;
		for ($i = 0; $i < $max; $i++) {
			array_unshift($composition, array_pop($composition));
			if (!in_array($composition, $swapped)) {
				$swapped[] = $composition;
			}
		}
		return $swapped;
	}

	/**
	 * Helper function for run(): after the collection is completed using
	 * getFilled() aand getSwapped(), the collection array is of 2 levels: bring
	 * back to one (as it was before inserting arrays)
	 * @param array $collection
	 * @return array
	 */
	private function flattenCollection(array $collection): array {
		$flattened = [];
		foreach ($collection as $composition) {
			if (is_integer(current($composition))) {
				$flattened[] = $composition;
				continue;
			}
			if (is_array(current($composition))) {
				foreach ($composition as $sub) {
					$flattened[] = $sub;
				}
				continue;
			}
		}
		return $flattened;
	}

}
