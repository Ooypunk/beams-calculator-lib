<?php

namespace Ooypunk\BeamsCalculatorLib\Partitions;

class Indexer {

	public function doIndexing(array $collection): array {
		$indexed = [];
		foreach ($collection as $partition) {
			$part_count = array_sum($partition);
			$part_indexes = range(1, $part_count);

			for ($j = 1; $j <= $part_count; $j++) {
				$temp_indexes = $part_indexes;
				$material_arr = [];
				foreach ($partition as $count) {
					$part_arr = [];
					for ($i = 1; $i <= $count; $i++) {
						$part_arr[] = array_shift($temp_indexes);
					}
					$material_arr[] = $part_arr;
				}
				$indexed[] = $material_arr;

				// Move last element of part_indexes to first position
				array_unshift($part_indexes, array_pop($part_indexes));
			}
		}
		return $indexed;
	}

}
