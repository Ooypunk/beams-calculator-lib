<?php

namespace Ooypunk\BeamsCalculatorLib\Calculator;

use Ooypunk\BeamsCalculatorLib\Partitions\Limiter;
use Ooypunk\BeamsCalculatorLib\Partitions\Completer;
use Ooypunk\BeamsCalculatorLib\Partitions\Partitioner;
use Ooypunk\BeamsCalculatorLib\Partitions\Indexer;
use Ooypunk\BeamsCalculatorLib\Scenarios\Scenario;
use Ooypunk\BeamsCalculatorLib\Materials\UsedMaterial;
use Ooypunk\BeamsCalculatorLib\Materials\Store as MaterialsStore;
use Ooypunk\BeamsCalculatorLib\Materials\Group;
use Ooypunk\BeamsCalculatorLib\Parts\PartsList;
use Ooypunk\BeamsCalculatorLib\Time\MicrotimeMark;

/**
 * Calculator:
 * - divide materials into groups (based on dimensions (width/height)),
 * - match parts with these groups
 * - calculate how many possibilities there are, create a scenario for each
 * - calculate the waste for each scenario
 */
class Calculator {

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Materials\Store
	 */
	private $materials_store;

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Parts\PartsList
	 */
	private $parts_list;

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Materials\Group[]
	 */
	private $groups = [];

	public function __construct(MaterialsStore $materials_store, PartsList $parts_list) {
		$this->materials_store = $materials_store;
		$this->parts_list = $parts_list;
	}

	/*
	 * General getters
	 */

	public function getMaterialsStore(): MaterialsStore {
		return $this->materials_store;
	}

	public function getPartsList(): PartsList {
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
		MicrotimeMark::init();

		// Get materials store, grouped on width and height
		$grouped_store = $this->materials_store->getGroupedStore();
		MicrotimeMark::mark('Materials store got');

		// Add parts to their corresponding group (match on width and height)
		$grouped_store->loadPartsListIntoGroups($this->parts_list);
		MicrotimeMark::mark('Parts loaded into group');

		// Now get these groups (materials/parts combinations)
		$groups = $grouped_store->getGroupsWithParts();
		MicrotimeMark::mark('Get groups from store');

		// Add partitions list to each
		$this->addPartitionsToGroups($groups);
		MicrotimeMark::mark('Partitions added to groups');

		// Add indexation list to each
		$this->addIndexationToGroups($groups);
		MicrotimeMark::mark('Indexation added to groups');

		// Fill scenarios with "indexed": for each group, add scenario's for all
		// "indexed" entries, filled with materials and parts (according to
		// their respective "indexed" entry)
		$this->fillScenariosWithGroupsIndexed($groups);
		MicrotimeMark::mark('fillScenariosWithGroupsIndexed');

		$this->groups = $groups;
	}

	/**
	 * Get list of scenario's (one for each group), sort by "least waste"
	 * @return \Ooypunk\BeamsCalculatorLib\Scenarios\Scenario[]
	 * @throws \Exception Thrown when $groups is empty: assumption is made that
	 * runCalc() has not been run first
	 */
	public function getLeastWasteScenarios(): array {
		$scenarios = [];
		foreach ($this->groups as $group) {
			$scenario = $group->getLeastWasteScenario();
			if ($scenario instanceof Scenario) {
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
	 * @param \Ooypunk\BeamsCalculatorLib\Materials\Group $group
	 * @return void
	 */
	private function fillScenariosWithGroupIndexed(\Ooypunk\BeamsCalculatorLib\Materials\Group $group): void {
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
	 * @param \Ooypunk\BeamsCalculatorLib\Materials\Group $group
	 * @param array $index_arr
	 * @return \Ooypunk\BeamsCalculatorLib\Scenarios\Scenario
	 */
	private function getScenarioWithIndexed(Group $group, array $index_arr): Scenario {
		/**
		 * @var \Ooypunk\BeamsCalculatorLib\Materials\Store
		 */
		$materials = $group->getMaterials();
		/**
		 * @var \Ooypunk\BeamsCalculatorLib\Parts\PartsList
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
