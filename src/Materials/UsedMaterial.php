<?php

namespace Lib\Materials;

use Lib\Materials\Material;
use Lib\Parts\Part;

class UsedMaterial {

	/**
	 * @var \Lib\Materials\Material
	 */
	private $material;

	/**
	 * @var \Lib\Parts\Part[]
	 */
	private $parts = [];

	public function __construct(Material $material) {
		$this->material = $material;
	}

	/*
	 * General getters
	 */

	public function getMaterial(): Material {
		return $this->material;
	}

	public function getParts(): array {
		return $this->parts;
	}

	/*
	 * Other
	 */

	public function addPart(Part $part): void {
		$this->parts[] = $part;
	}

	public function partFits(Part $part): bool {
		$avail_length = $this->getAvailableLength();
		$needed_length = $part->getLength();
		return $needed_length <= $avail_length;
	}

	private function getAvailableLength(): int {
		$used_length = $this->getUsedLength();
		$total_length = $this->getTotalLength();
		return $total_length - $used_length;
	}

	public function getUsedLength(): int {
		$saw_thickness = \Lib\Register\Register::getInstance()->saw_width;
		$cuts = count($this->getParts()) - 1;
		$length = $cuts * $saw_thickness;
		foreach ($this->getParts() as $part) {
			$length += $part->getLength();
		}
		return $length;
	}

	public function getTotalLength(): int {
		return $this->getMaterial()->getLength();
	}

	public function getWasteRatio(): float {
		$total_len = $this->getTotalLength();
		$used_len = $this->getUsedLength();
		$waste_pct = (($total_len - $used_len) / $total_len) * 100;
		return $waste_pct;
	}

	public function getMaterialLabel(): string {
		return $this->getMaterial()->getLabel();
	}

}
