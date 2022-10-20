<?php

namespace Ooypunk\BeamsCalculatorLib\Base;

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

	/**
	 * @var int
	 */
	protected $number = 0;

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
		$label = $this->label;
		if (empty($label)) {
			$cls_name_parts = explode('\\', get_called_class());
			$label = sprintf(
					'%s [%dx%dx%d]',
					end($cls_name_parts),
					$this->getWidth(),
					$this->getHeight(),
					$this->getLength()
			);
		}
		if ($this->number > 0) {
			$label .= ' (' . $this->number . ')';
		}
		return $label;
	}

	/**
	 * @return int
	 */
	public function getNumber(): int {
		return $this->number;
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

	public function setNumber(int $number): void {
		if (empty($this->label)) {
			return;
		}
		$this->number = $number;
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

	/*
	 * To/from array
	 */

	public function toArray(): array {
		$array = [
			'label' => $this->label,
			'width' => $this->width,
			'height' => $this->height,
			'length' => $this->length,
			'number' => $this->number,
		];
		return $array;
	}

	public function fromArray(array $array): void {
		$this->setLabel($array['label']);
		$this->setWidth($array['width']);
		$this->setHeight($array['height']);
		$this->setLength($array['length']);
		if (isset($array['number'])) {
			$this->setNumber($array['number']);
		}
	}

}
