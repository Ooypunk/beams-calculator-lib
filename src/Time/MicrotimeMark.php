<?php

namespace Ooypunk\BeamsCalculatorLib\Time;

class MicrotimeMark {

	/**
	 * @var \DateTime|float Starting point in time
	 */
	protected static $start;

	/**
	 * @var \DateTime|float Ending point in time
	 */
	protected static $end;

	/**
	 * @var array Time markings
	 */
	protected static $marks = [];

	/**
	 * Start timer
	 */
	public static function init() {
		self::setStart(microtime(true));
	}

	/**
	 * Set starting time. Meant for internal use only!
	 * @param float $microtime Microtime
	 */
	public static function setStart($microtime) {
		if (!is_float($microtime)) {
			$msg = _('Error in MicrotimeMark::setStart(): DateTime expected');
			throw new \BadFunctionCallException($msg);
		}
		self::$start = (float) $microtime;
	}

	/**
	 * Set ending time. Meant for internal use only!
	 * @param float $microtime Microtime
	 */
	public static function setEnd($microtime) {
		if (!is_float($microtime)) {
			$msg = _('Error in MicrotimeMark::setEnd(): DateTime expected');
			throw new \BadFunctionCallException($msg);
		}
		self::$end = (float) $microtime;
	}

	/**
	 * Reset timer and list of marks
	 */
	public static function reset() {
		self::$start = null;
		self::$end = null;
		self::$marks = array();
	}

	/**
	 * Set mark: record current time, marked by given label
	 * @param string $label Label to identify this marking
	 */
	public static function mark($label = '') {
		if (self::$start === null) {
			self::init();
		}
		self::addMark($label, microtime(true));
	}

	/**
	 * Add Mark to list. Meant for internal use only!
	 * @param string $label Label
	 * @param float $microtime Microtime float
	 */
	public static function addMark($label, $microtime) {
		$mark = new \stdClass();
		$mark->microtime = (float) $microtime;
		$mark->label = (string) $label;
		self::$marks[] = $mark;
	}

	/**
	 * Get list of marks (timestamps), formatted for use on CLI
	 * @return string Output string(s)
	 */
	public static function getMarksCli() {
		if (count(self::$marks) === 0) {
			return 'Geen markeringen gevonden';
		}
		// @codeCoverageIgnoreStart
		if (self::$end === null) {
			self::setEnd(microtime(true));
		}
		// @codeCoverageIgnoreEnd

		$last = self::$start;
		$rows = [];
		foreach (self::$marks as $mark) {
			$diff = $mark->microtime - $last;
			$last = $mark->microtime;
			$row = [$mark->microtime, number_format($diff, 4) . 'ms', $mark->label];
			$rows[] = $row;
		}

		// Calculate and add total
		$diff = self::$end - self::$start;
		$row = ["\t", number_format($diff, 4), ' '];
		$rows[] = $row;

		// Do output
		foreach ($rows as $row) {
			print implode("\t", $row) . "\n";
		}
		print "\n";
	}

}
