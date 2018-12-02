<?php


abstract class AbstractSerializer {

	protected $REQUIRED_FIELDS = [];
	protected $raw_data = [];
	protected $instance = null;
	protected $errors = [];
	protected $many = false;

	public function __construct($raw_data, $instance=null, $many=false) {
		$this->raw_data = $raw_data;
		$this->instance = $instance;
		$this->many = $many;
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


abstract class AbstractModelSerializer extends AbstractSerializer {

	protected $MODEL_FIELDS = [];
	protected $MODEL_CLASS = null;

	private function __deflateInstance($instance) {
		$serialized = array();
		foreach ( $this->MODEL_FIELDS as $field ) {

			$fieldname = $field['fieldname'];

			if ($field['method']) {
				$serialized[ $fieldname ] = $instance->{$field['method']}();
			}
			else {
				if ( $instance->{$fieldname} != null ) {
					$serialized[ $fieldname ] = $instance->{$fieldname};
				}

			}
		}

		return $serialized;
	}

	public function serialize(): array {
		if ($this->many) {
			$serialized = array();
			foreach($this->instance as $instance) {
				array_push(
					$serialized,
					$this->__deflateInstance($instance)
				);
			}

			return $serialized;
		}

		return $this->__deflateInstance($this->instance);
	}
}