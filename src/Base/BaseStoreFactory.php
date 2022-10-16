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

}
