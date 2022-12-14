<?php

namespace Ooypunk\BeamsCalculatorLib\Materials;

use Ooypunk\BeamsCalculatorLib\Base\BaseStoreFactory;

class StoreFactory extends BaseStoreFactory {

	public function fromPost(array $post): Store {
		$store = new Store();

		$rows = [];
		$elems_count = count(current($post));
		for ($i = 0; $i < $elems_count; $i++) {
			$row = [];
			foreach (self::MANDATORY_KEYS as $key) {
				$row[$key] = $post[$key][$i];
			}
			if (isset($post['qty'][$i])) {
				$row['qty'] = $post['qty'][$i];
			}
			$rows[] = $row;
		}

		foreach ($rows as $row) {
			$this->addMappedRow($row, $store);
		}

		return $store;
	}

	protected function addMappedRow(array $mapped_row, Store $list): void {
		$qty = 1;
		if (array_key_exists('qty', $mapped_row)) {
			$qty = intval($mapped_row['qty']);
		}

		// Compose new Material
		$length = intval($mapped_row['length']);
		$width = intval($mapped_row['width']);
		$height = intval($mapped_row['height']);
		$label = strval($mapped_row['label']);
		$base_mtrl = new Material($length, $width, $height, $label);

		for ($i = 1; $i <= $qty; $i++) {
			$child = clone $base_mtrl;

			if ($qty > 1) {
				$child->setNumber($i);
			}

			$list->addMaterial($child);
		}
	}

	/**
	 * Get new, filled Materials Store from CSV file
	 * @param string $file_path
	 * @param array $header_map
	 * @return Store
	 */
	public function fromCsvFile(string $file_path, array $header_map = []): Store {
		$store = new Store();

		foreach ($this->getDataFromCsvFile($file_path, $header_map) as $row) {
			$this->addMappedRow($row, $store);
		}

		return $store;
	}

}
