<?php

namespace Ooypunk\BeamsCalculatorLib\Scenarios;

/**
 * Store/collection for Scenarios
 */
class Store {

	/**
	 * @var Scenario[]
	 */
	private $scenarios = [];

	/**
	 * Add scenario to store
	 * @param Scenario $scenario
	 * @return void
	 */
	public function addScenario(Scenario $scenario): void {
		$this->scenarios[] = $scenario;
	}

	/**
	 * Get list of all scenarios in store
	 * @return Scenario[]
	 */
	public function getScenarios(): array {
		return $this->scenarios;
	}

	/**
	 * Get list of scenarios that, after having checked if all parts fit, have
	 * no exceptions.
	 * @return Scenario[]
	 */
	public function getScenariosWithoutException(): array {
		$scenarios = [];
		foreach ($this->scenarios as $scenario) {
			$scenario->assertPartsFitInMaterials();
			if (!$scenario->hasExceptions()) {
				$scenarios[] = $scenario;
			}
		}
		return $scenarios;
	}

	/**
	 * Get scenario that has the least waste.
	 * Doesn't function until runCalc() has been run.
	 * @return Scenario|null
	 */
	public function getLeastWasteScenario(): ?Scenario {
		$scenarios = $this->getScenariosWithoutException();
		if (count($scenarios) === 0) {
			return null;
		}

		// Sort scenarios by waste ratio
		usort($scenarios, function (Scenario $scen_a, Scenario $scen_b) {
			return $scen_a->getWasteRatio() <=> $scen_b->getWasteRatio();
		});

		return current($scenarios);
	}

}
