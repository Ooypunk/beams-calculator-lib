<?php

namespace Lib\Calculator;

use \Lib\Partitions\Limiter;
use \Lib\Partitions\Completer;
use \Lib\Partitions\Partitioner;
use \Lib\Partitions\Indexer;
use \Lib\Scenarios\Scenario;
use \Lib\Materials\UsedMaterial;
use \Lib\Materials\Store as MaterialsStore;
use Lib\Parts\PartsList;

/**
 * Calculator:
 * - divide materials into groups (based on dimensions (width/height)),
 * - match parts with these groups
 * - calculate how many possibilities there are, create a scenario for each
 * - calculate the waste for each scenario
 */
class Calculator {

	/**
	 * @var \Lib\Materials\Store
	 */
	private $materials_store;

	/**
	 * @var \Lib\Parts\PartsList
	 */
	private $parts_list;

	/**
	 * @var \Lib\Materials\Group[]
	 */
	private $groups = [];

	public function __construct(MaterialsStore $materials_store, PartsList $parts_list) {
		$this->materials_store = $materials_store;
		$this->parts_list = $parts_list;
	}

	/*
	 * General getters
	 */

	public function getMaterialsStore(): \Lib\Materials\Store {
		return $this->materials_store;
	}

	public function getPartsList(): \Lib\Parts\PartsList {
		return $this->parts_list;
	}

	/*
	 * Core
	 */

	/**
	 * Run calculator:
	 * .- get materials and parts grouped (by width and height)
	 * .- add partitions to each group: all ways to divide the parts over the
	 * materials (number of parts per material)
	 * .- add indexations to each group: using the partitions, determine which
	 * parts per material (checking if parts fit in the materials is skipped
	 * here)
	 * .- add scenario's to each group: create a scenario for each indexation
	 * @return void
	 */
	public function runCalc(): void {
		// Get materials store, grouped on width and height
		$grouped_store = $this->materials_store->getGroupedStore();

		// Add parts to their corresponding group (match on width and height)
		$grouped_store->loadPartsListIntoGroups($this->parts_list);

		// Now get these groups (materials/parts combinations)
		$groups = $grouped_store->getGroupsWithParts();

		// Add partitions list to each
		$this->addPartitionsToGroups($groups);

		// Add indexation list to each
		$this->addIndexationToGroups($groups);

		// Fill scenarios with "indexed": for each group, add scenario's for all
		// "indexed" entries, filled with materials and parts (according to
		// their respective "indexed" entry)
		$this->fillScenariosWithGroupsIndexed($groups);

		$this->groups = $groups;
	}

	/**
	 * Get list of scenario's (one for each group), sort by "least waste"
	 * @return \Lib\Scenarios\Scenario[]
	 * @throws \Exception Thrown when $groups is empty: assumption is made that
	 * runCalc() has not been run first
	 */
	public function getLeastWasteScenarios(): array {
		$scenarios = [];
		foreach ($this->groups as $group) {
			$scenario = $group->getLeastWasteScenario();
			if ($scenario instanceof \Lib\Scenarios\Scenario) {
				$scenarios[] = $scenario;
			}
		}
		return $scenarios;
	}

	/*
	 * Helpers
	 */

	/**
	 * Helper function for runCalc(): add partitions list to each group
	 * @param array $groups
	 * @return void
	 */
	private function addPartitionsToGroups(array $groups): void {
		foreach ($groups as $group) {
			$nr_of_parts = $group->getPartsCount();
			$nr_of_matrs = $group->getMaterialsCount();

			$limiter = new Limiter($nr_of_matrs);
			$completer = new Completer($nr_of_matrs);
			$partitioner = new Partitioner($limiter, $completer);
			$partitions = $partitioner->getPartitioned($nr_of_parts);
			$group->setPartitions($partitions);
		}
	}

	/**
	 * Helper function for runCalc(): add indexation list to each group
	 * @param array $groups
	 * @return void
	 */
	private function addIndexationToGroups(array $groups): void {
		$indexer = new Indexer();
		foreach ($groups as $group) {
			$partitions = $group->getPartitions();
			$indexed = $indexer->doIndexing($partitions);
			$group->setIndexed($indexed);
		}
	}

	/**
	 * Helper function for runCalc(): compose list of scenario's (based on the
	 * indexation list in group) to each group
	 * @param array $groups
	 * @return void
	 */
	private function fillScenariosWithGroupsIndexed(array $groups): void {
		foreach ($groups as $group) {
			$this->fillScenariosWithGroupIndexed($group);
		}
	}

	/**
	 * Helper function for fillScenariosWithGroupsIndexed(): compose list of
	 * scenario's (based on the indexation list in group) to given group
	 * @param \Lib\Materials\Group $group
	 * @return void
	 */
	private function fillScenariosWithGroupIndexed(\Lib\Materials\Group $group): void {
		$indexed = $group->getIndexed();
		if (!is_iterable($indexed)) {
			return;
		}
		foreach ($indexed as $index_arr) {
			$scenario = $this->getScenarioWithIndexed($group, $index_arr);
			$group->addScenario($scenario);
		}
	}

	/**
	 * Helper function for fillScenariosWithGroupIndexed(): create a new
	 * scenario for given indexation, filled with materials and parts
	 * @param \Lib\Materials\Group $group
	 * @param array $index_arr
	 * @return \Lib\Scenarios\Scenario
	 */
	private function getScenarioWithIndexed(\Lib\Materials\Group $group, array $index_arr): \Lib\Scenarios\Scenario {
		/**
		 * @var \Lib\Materials\Store
		 */
		$materials = $group->getMaterials();
		/**
		 * @var \Lib\Parts\PartsList
		 */
		$parts = $group->getParts();

		$scenario = new Scenario();
		$scenario->setIndexed($index_arr);
		foreach ($index_arr as $mat_key => $part_idxs) {
			if (count($part_idxs) === 0) {
				continue;
			}

			$material = $materials->getByIndex((int) $mat_key + 1);
			$used_material = new UsedMaterial($material);
			$scenario->addUsedMaterial($used_material);

			foreach ($part_idxs as $part_idx) {
				$part = $parts->getByIndex($part_idx);
				$used_material->addPart($part);
			}
		}
		return $scenario;
	}

	/*
	 * Other
	 */

	/**
	 * Get number of calculations to be done
	 * @return int
	 */
	public function getCalculationsCount(): int {
		$count = 0;
		foreach ($this->groups as $group) {
			$indexed = $group->getIndexed();
			if (!is_countable($indexed)) {
				continue;
			}
			$count += count($indexed);
		}
		return $count;
	}

}
