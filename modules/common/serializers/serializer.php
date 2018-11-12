<?php


abstract class AbstractSerializer {

	protected $REQUIRED_FIELDS = [];
	protected $raw_data = [];
	protected $instance = null;
	protected $errors = [];

	public function __construct($raw_data, $instance=null) {
		$this->raw_data = $raw_data;
		$this->instance = $instance;
	}

	public function is_valid(): bool {
		foreach ($this->REQUIRED_FIELDS as $field) {
			if (!array_key_exists($field, $this->raw_data)) {
				array_push(
					$this->errors,
					'abc'
				);
			}
		}
		return sizeof($this->errors) === 0;
	}

	public abstract function serialize(): array;
}
