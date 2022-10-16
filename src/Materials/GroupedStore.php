<?php

namespace Lib\Materials;

use Lib\Materials\Group;
use Lib\Exceptions\GroupNotFound;

/**
 * Materials store, but grouped by width and height: each combination is a
 * Group.
 */
class GroupedStore {

	/**
	 * @var array Dimensions map: all materials grouped by width and height
	 */
	private $dims_map;

	/**
	 * Constructor: load Materials Store, create/fill dimensions map from it
	 * @param \Lib\Materials\Store $store
	 */
	public function __construct(\Lib\Materials\Store $store) {
		$this->dims_map = [];

		$this->loadStoreAsDimsMap($store);
	}

	private function loadStoreAsDimsMap(\Lib\Materials\Store $store): void {
		foreach ($store->getMaterials() as $material) {
			$width = $material->getWidth();
			$height = $material->getHeight();
			if (!isset($this->dims_map[$width][$height])) {
				$this->dims_map[$width][$height] = new Group();
			}
			$this->dims_map[$width][$height]->addMaterial($material);
		}
	}

	/**
	 * Add Part to the corresponding Group
	 * @param \Lib\Parts\Part $part
	 * @return void
	 * @throws \Lib\Exceptions\GroupNotFound Thrown if no group could be found
	 * for given part
	 */
	public function addPartToGroup(\Lib\Parts\Part $part): void {
		$group = $this->getGroupForPart($part);

		if ($group instanceof \Lib\Materials\Group) {
			$group->addPart($part);
			return;
		}

		$msg_raw = _('No Group found for Part "%s"');
		$msg = sprintf($msg_raw, $part->getLabelWithDims());
		throw new GroupNotFound($msg);
	}

	/**
	 * Get Group for given Part (collection of Parts and Materials that have the
	 * same width and height)
	 * @param \Lib\Parts\Part $part
	 * @return Group|null
	 */
	public function getGroupForPart(\Lib\Parts\Part $part): ?Group {
		$width = $part->getWidth();
		$height = $part->getHeight();

		if (isset($this->dims_map[$width][$height])) {
			return $this->dims_map[$width][$height];
		}
		if (isset($this->dims_map[$height][$width])) {
			return $this->dims_map[$height][$width];
		}
		return null;
	}

	/*
	 * Other
	 */

	public function getGroups(): array {
		$groups = [];
		foreach ($this->dims_map as $width_arr) {
			foreach ($width_arr as $group) {
				$groups[] = $group;
			}
		}
		return $groups;
	}

	public function getGroupsWithParts(): array {
		$groups = [];
		foreach ($this->getGroups() as $group) {
			if ($group->hasParts()) {
				$groups[] = $group;
			}
		}
		return $groups;
	}

	public function loadPartsListIntoGroups(\Lib\Parts\PartsList $parts_list): void {
		foreach ($parts_list->getParts() as $part) {
			$this->addPartToGroup($part);
		}
	}

}
