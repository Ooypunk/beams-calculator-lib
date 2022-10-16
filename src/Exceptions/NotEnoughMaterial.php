<?php

namespace Lib\Exceptions;

/**
 * Throw this exception when more material is asked than is available
 */
class NotEnoughMaterial extends \Exception {

	/**
	 * @var \Lib\Materials\Group|null
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
	 * @param \Lib\Materials\Group $group
	 */
	public function setGroup(\Lib\Materials\Group $group): void {
		$this->group = $group;
	}

	/**
	 * Get group
	 * @return \Lib\Materials\Group
	 */
	public function getGroup(): ?\Lib\Materials\Group {
		return $this->group;
	}

}
