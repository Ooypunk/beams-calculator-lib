<?php

namespace Ooypunk\BeamsCalculatorLib\Exceptions;

use Ooypunk\BeamsCalculatorLib\Materials\Group as MaterialsGroup;

/**
 * Throw this exception when more material is asked than is available
 */
class NotEnoughMaterial extends \Exception {

	/**
	 * @var MaterialsGroup|null
	 */
	private $group = null;

	public function __construct(string $message = '', int $code = 0, \Throwable $previous = NULL) {
		if ($message === '') {
			$message = _('Not enough material');
		}
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Set group
	 * @param \Ooypunk\BeamsCalculatorLib\Materials\Group $group
	 */
	public function setGroup(MaterialsGroup $group): void {
		$this->group = $group;
	}

	/**
	 * Get group
	 * @return \Ooypunk\BeamsCalculatorLib\Materials\Group
	 */
	public function getGroup(): ?MaterialsGroup {
		return $this->group;
	}

}
