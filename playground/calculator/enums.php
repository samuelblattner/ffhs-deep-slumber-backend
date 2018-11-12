<?php

class MathFunction {
	const NOOP = 'noop';
	const ADDITION = 'add';
	const SUBTRACTION = 'sub';
	const MULTIPLICATION = 'mul';
	const DIVISION = 'div';
	const MODULO = 'mod';

	private static $operations = [
		'noop' => self::NOOP,
		'add' => self::ADDITION,
		'sub' => self::SUBTRACTION,
		'mul' => self::MULTIPLICATION,
		'div' => self::DIVISION,
		'mod' => self::MODULO
	];

	private $val = '';

	public function __construct($initVal) {
		if (in_array($initVal, MathFunction::$operations)) {
			$this->val = MathFunction::$operations[$initVal];
		} else {
			$this->val = MathFunction::NOOP;
		}
	}

	public function __toString() {
		return $this->val;
	}
}
