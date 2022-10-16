<?php

namespace Ooypunk\BeamsCalculatorLib\Partitions;

use \Exception;
use \Ooypunk\BeamsCalculatorLib\Partitions\Partitioner;

/**
 * Helper class for Partitioner, doesn't work without it.
 * The Limiter limits the size of the "partitions" (the number of materials).
 */
class Limiter {

	/**
	 * @var int Limit
	 */
	private $limit;

	public function __construct(int $limit) {
		if ($limit < 1) {
			throw new Exception('Limit should be higher than zero');
		}
		$this->limit = $limit;
	}

	public function run(Partitioner $parent): void {
		$collection = [];
		foreach ($parent->getCollection() as $composition) {
			$filtered = array_filter($composition);
			if (count($filtered) > $this->limit) {
				continue;
			}
			$collection[] = $filtered;
		}
		$parent->setCollection($collection);
	}

}
