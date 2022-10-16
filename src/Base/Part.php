<?php

namespace Lib\Base;

/**
 * Abstract/Base class for Part and Material
 */
abstract class Part {

	/**
	 * @var int
	 */
	protected $length;

	/**
	 * @var int
	 */
	protected $width;

	/**
	 * @var int
	 */
	protected $height;

	/**
	 * @var string
	 */
	protected $label;

	public function __construct(int $length, int $width, int $height, string $label) {
		$this->length = $length;
		$this->width = $width;
		$this->height = $height;
		$this->label = $label;
	}

	/*
	 * General getters
	 */

	/**
	 * @return int Length
	 */
	public function getLength(): int {
		return $this->length;
	}

	/**
	 * @return int Width
	 */
	public function getWidth(): int {
		return $this->width;
	}

	/**
	 * @return int Height
	 */
	public function getHeight(): int {
		return $this->height;
	}

	/**
	 * @return string Label
	 */
	public function getLabel(): string {
		if (empty($this->label)) {
			$cls_name_parts = explode('\\', get_called_class());
			return sprintf(
					'%s [%dx%dx%d]',
					end($cls_name_parts),
					$this->getWidth(),
					$this->getHeight(),
					$this->getLength()
			);
		}
		return $this->label;
	}

	/*
	 * General setters
	 */

	/**
	 * @param int $length
	 * @return self Fluent setter
	 */
	public function setLength(int $length): self {
		$this->length = $length;
		return $this;
	}

	/**
	 * @param int $width Width
	 * @return self Fluent setter
	 */
	public function setWidth(int $width): self {
		$this->width = $width;
		return $this;
	}

	/**
	 * @param int $height Height
	 * @return self Fluent setter
	 */
	public function setHeight(int $height): self {
		$this->height = $height;
		return $this;
	}

	/**
	 * @param string $label Label
	 * @return self Fluent setter
	 */
	public function setLabel(string $label): self {
		$this->label = $label;
		return $this;
	}

	/*
	 * Other
	 */

	public function getLabelWithDims(): string {
		$filled = sprintf(
				'%s [%dx%dx%d]',
				$this->getLabel(),
				$this->getWidth(),
				$this->getHeight(),
				$this->getLength()
		);
		return $filled;
	}

	public function setNumber(int $number): void {
		if (empty($this->label)) {
			return;
		}
		$this->label .= ' (' . $number . ')';
	}

}
