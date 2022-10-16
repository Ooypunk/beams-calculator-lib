<?php

namespace Ooypunk\BeamsCalculatorLib\Register;

/**
 * Register: central point for holding settings
 */
class Register {

	/**
	 * @var \Ooypunk\BeamsCalculatorLib\Register\Register|null Hold the class instance.
	 */
	private static $instance = null;

	/**
	 * @var array Variables
	 */
	private $vars = [];

	/**
	 * The constructor is private to prevent initiation with outer code:
	 * use getInstance()
	 */
	private function __construct() {
		// pass
	}

	/**
	 * Get instance of this class: instantiate if not done already (singleton:
	 * only one instance)
	 * @return Register
	 */
	public static function getInstance(): Register {
		if (self::$instance == null) {
			self::$instance = new Register();
		}
		return self::$instance;
	}

	/**
	 * Set a variable on register
	 * @param string $name Name of variable
	 * @param mixed $value Value of variable
	 * @return void
	 */
	public function __set(string $name, $value): void {
		$this->vars[$name] = $value;
	}

	/**
	 * Get a variable from register
	 * @param string $name Name of variable
	 * @return mixed Value of variable; null if not found
	 */
	public function __get(string $name) {
		if (!array_key_exists($name, $this->vars)) {
			return null;
		}
		return $this->vars[$name];
	}

	/**
	 * Check if given variable is set
	 * @param string $name Name of variable
	 * @return bool
	 */
	public function __isset(string $name): bool {
		return array_key_exists($name, $this->vars);
	}

}
