<?php

namespace Lib\Base;

class BaseStoreFactory {

	const MANDATORY_KEYS = ['width', 'height', 'length', 'label'];

	protected function mapHeaders(array $row, array $header_map = []): array {
		if (count($header_map) === 0) {
			return $row;
		}
		$mapped = [];
		foreach ($header_map as $from => $to) {
			if (array_key_exists($from, $row)) {
				$mapped[$to] = $row[$from];
			}
		}
		return $mapped;
	}

	protected function getDataFromCsvFile(string $filename, array $header_map = []): array {
		$reader = \League\Csv\Reader::createFromPath($filename, 'r');
		$reader->setDelimiter(';');
		$reader->setHeaderOffset(0);

		$data = [];
		foreach ($reader->getRecords() as $row) {
			$data[] = $this->mapHeaders($row, $header_map);
		}
		return $data;
	}

}
