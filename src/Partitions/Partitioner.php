<?php

namespace Ooypunk\BeamsCalculatorLib\Partitions;

use Ooypunk\BeamsCalculatorLib\Partitions\Limiter;
use Ooypunk\BeamsCalculatorLib\Partitions\Completer;
use Exception;

/**
 * Partitioner: generate list of partitions for given number ("list of ways to
 * divide x balls into y boxes").
 * For example, with number = 3, then the possible partitions are:
 * - 3
 * - 2, 1
 * - 1, 1, 1
 * Note that "2,1" is considered the same as "1,2", this is fixed by
 * completeCollection().
 * 
 * A limit can be given, this limits the number of options ("y boxes"); if not
 * given, number of options is unlimited.
 */
class Partitioner {

	/**
	 * @var int[] Composition/current partition
	 */
	private $composition = [];

	/**
	 * @var array List of partitions
	 */
	private $collection = [];

	/**
	 * @var int Current index
	 */
	private $cur_idx = 0;

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Partitions\Limiter
	 */
	private $limiter;

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Partitions\Completer
	 */
	private $completer;

	public function __construct(Limiter $limiter, Completer $completer) {
		$this->limiter = $limiter;
		$this->completer = $completer;
	}

	/*
	 * General getters
	 */

	public function getCollection(): array {
		return $this->collection;
	}

	/*
	 * General setters
	 */

	/**
	 * Set Collection
	 * @param array $collection
	 * @return self
	 */
	public function setCollection(array $collection): self {
		$this->collection = $collection;
		return $this;
	}

	/**
	 * Core: get list of partitions for number $max ("x balls"), limited to
	 * $limit ("y boxes")
	 * @param int $max Number to partitions for
	 * @return array List of partitions
	 */
	public function getPartitioned(int $max): array {
		if ($max < 1) {
			throw new Exception(_('Number should be greater than 0'));
		}
		$this->init($max);

		/*
		 * Create a while loop which first prints current partition, then
		 * generates next partition. The loop stops when the current partition
		 * has all 1s.
		 */
		while (true) {
			$this->collection[] = array_slice($this->composition, 0, ($this->cur_idx + 1));

			/*
			 * Generate next partition:
			 */
			// Initialize val=0.
			$rem_val = 0;

			// Find the rightmost non-one value in p[]. Also, update the val so
			// that we know how much value can be accommodated.
			while ($this->cur_idx >= 0 && $this->composition[$this->cur_idx] == 1) {
				$rem_val += $this->composition[$this->cur_idx];
				$this->cur_idx--;
			}

			// If k < 0, all the values are 1 so there are no more partitions: done
			if ($this->cur_idx < 0) {
				break;
			}

			// ..else, decrease the p[k] found above and adjust the val.
			$this->composition[$this->cur_idx]--;
			$rem_val++;

			// If val is more, then the sorted order is violated. Divide val in
			// different values of size p[k] and copy these values at different
			// positions after p[k].
			while ($rem_val > $this->composition[$this->cur_idx]) {
				$this->composition[$this->cur_idx + 1] = $this->composition[$this->cur_idx];
				$rem_val = $rem_val - $this->composition[$this->cur_idx];
				$this->cur_idx++;
			}

			// Copy val to next position and increment position.
			$this->composition[$this->cur_idx + 1] = $rem_val;
			$this->cur_idx++;
		}

		$this->limitCollection();
		$this->completeCollection();
		return $this->collection;
	}

	/**
	 * Helper function for getPartitioned(): (re)set variables to start off with
	 * @param int $max Number to partitions for
	 * @return void
	 */
	private function init(int $max): void {
		/**
		 * Declare an array to store a partition p[m].
		 * int p[m];
		 * @var int[]
		 */
		$this->composition = array_fill(0, $max, 0);

		/**
		 * Set Index of last element k in a partition to 0
		 * int k = 0;
		 * @var int
		 */
		$this->cur_idx = 0;

		// Initialize first partition as number itself, p[k]=m
		$this->composition[$this->cur_idx] = $max;

		// Reset collection
		$this->collection = [];
	}

	/**
	 * Helper function for getPartitioned(): limit options in partitions to
	 * given number
	 * @return void
	 */
	private function limitCollection(): void {
		$this->limiter->run($this);
	}

	/**
	 * Helper function for getPartitioned(): run getSwapped()/flattenCollection()
	 * for partitions that can be applied in more than 1 way
	 * @return void
	 */
	private function completeCollection(): void {
		$this->completer->run($this);
	}

}
