<?php

namespace Lib\Materials;

use \Lib\Materials\Store as MaterialsStore;
use \Lib\Parts\PartsList;
use \Lib\Scenarios\Store as ScenariosStore;

/**
 * Group: collection of materials of the same width and height, plus collection
 * of parts of the same width and height
 */
class Group {

	/**
	 * @var \Lib\Materials\Store
	 */
	private $materials;

	/**
	 * @var \Lib\Parts\PartsList
	 */
	private $parts;

	/**
	 * @var array|null
	 */
	private $partitions;

	/**
	 * @var array|null
	 */
	private $indexed;

	/**
	 * @var \Lib\Scenarios\Store
	 */
	private $scenarios;

	/**
	 * Constructor: init materials and parts collections
	 */
	public function __construct() {
		$this->materials = new MaterialsStore();
		$this->parts = new PartsList();
		$this->scenarios = new ScenariosStore();
	}

	/*
	 * "Getter" functions
	 */

	/**
	 * Get Materials collection
	 * @return \Lib\Materials\Store
	 */
	public function getMaterials(): \Lib\Materials\Store {
		return $this->materials;
	}

	/**
	 * Get Parts collection
	 * @return \Lib\Parts\PartsList
	 */
	public function getParts(): \Lib\Parts\PartsList {
		return $this->parts;
	}

	public function getScenarios(): \Lib\Scenarios\Store {
		return $this->scenarios;
	}

	/*
	 * "Adder" functions
	 */

	/**
	 * Add a Material to the collection
	 * @param Material $material
	 * @return void
	 */
	public function addMaterial(Material $material): void {
		$this->materials->addMaterial($material);
	}

	/**
	 * Add a Part to the collection
	 * @param \Lib\Parts\Part $part
	 * @return void
	 */
	public function addPart(\Lib\Parts\Part $part): void {
		$this->parts->addPart($part);
	}

	/**
	 * Add a Scenario to the collection
	 * @param \Lib\Scenarios\Scenario $scenario
	 * @return void
	 */
	public function addScenario(\Lib\Scenarios\Scenario $scenario): void {
		$this->scenarios->addScenario($scenario);
	}

	/*
	 * "Hasser" functions
	 */

	/**
	 * Check if any parts were added
	 * @return bool
	 */
	public function hasParts(): bool {
		return $this->parts->count() > 0;
	}

	/**
	 * Check if any materials were added
	 * @return bool
	 */
	public function hasMaterials(): bool {
		return $this->materials->count() > 0;
	}

	/*
	 * "count" functions
	 */

	public function getPartsCount(): int {
		return $this->parts->count();
	}

	public function getMaterialsCount(): int {
		return $this->materials->count();
	}

	/*
	 * Partitions
	 */

	public function getPartitions(): ?array {
		return $this->partitions;
	}

	public function setPartitions(array $partitions): void {
		$this->partitions = $partitions;
	}

	/*
	 * Indexed
	 */

	public function getIndexed(): ?array {
		return $this->indexed;
	}

	public function setIndexed(array $indexed): void {
		$this->indexed = $indexed;
	}

	public function getLeastWasteScenario(): ?\Lib\Scenarios\Scenario {
		return $this->getScenarios()->getLeastWasteScenario();
	}

}
