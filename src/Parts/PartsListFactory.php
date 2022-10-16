<?php

namespace Lib\Parts;

class PartsListFactory extends \Lib\Base\BaseStoreFactory {

	public function fromPost(array $post): PartsList {
		$store = new PartsList();

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

	public function fromCsvFile(string $filename, array $header_map = []): PartsList {
		$store = new PartsList();

		foreach ($this->getDataFromCsvFile($filename, $header_map) as $row) {
			$this->addMappedRow($row, $store);
		}

		return $store;
	}

	protected function addMappedRow(array $mapped_row, PartsList $list): void {
		$qty = 1;
		if (array_key_exists('qty', $mapped_row)) {
			$qty = intval($mapped_row['qty']);
		}

		// Compose new Part
		$length = intval($mapped_row['length']);
		$width = intval($mapped_row['width']);
		$height = intval($mapped_row['height']);
		$label = strval($mapped_row['label']);
		$base_part = new Part($length, $width, $height, $label);

		for ($i = 1; $i <= $qty; $i++) {
			$child = clone $base_part;

			if ($qty > 1) {
				$child->setNumber($i);
			}

			$list->addPart($child);
		}
	}

}
