<?php


abstract class AbstractSerializer {

	protected $REQUIRED_FIELDS = [];
	protected $raw_data = [];
	protected $instance = null;
	protected $errors = [];
	protected $many = false;

	public function __construct( $raw_data, $instance = null, $many = false ) {
		$this->raw_data = $raw_data;
		$this->instance = $instance;
		$this->many     = $many;
	}

	public function is_valid(): bool {
		if ( $this->raw_data == null ) {
			return false;
		}
		foreach ( $this->REQUIRED_FIELDS as $field ) {
			if ( ! array_key_exists( $field, $this->raw_data ) ) {
				array_push(
					$this->errors,
					'abc'
				);
			}
		}

		return sizeof( $this->errors ) === 0;
	}

	public abstract function serialize(): array;

	public function update() {
		throw new RuntimeException( 'Method not implemented.' );
	}

	public function getInstance() {
		return $this->instance;
	}

	public function setRawDataField($field, $value) {
		if ($this->raw_data == null) {
			$this->raw_data = array();
		}

		$this->raw_data[$field] = $value;
	}
}


abstract class AbstractModelSerializer extends AbstractSerializer {

	protected $MODEL_FIELDS = [];
	protected $MODEL_CLASS = null;
	protected $QUERY_CLASS = null;

	private function __deflateInstance( $instance ) {
		$serialized = array();
		if ( $instance == null ) {
			return $serialized;
		}
		foreach ( $this->MODEL_FIELDS as $field ) {

			$fieldname = $field['fieldname'];

			if ( $field['getter'] ) {
				if ( $field['getter'] === 'NOGET' ) {
					continue;
				}
				$serialized[ $fieldname ] = $instance->{$field['getter']}();
			} else {
				if ( $instance->{$fieldname} != null ) {
					$serialized[ $fieldname ] = $instance->{$fieldname};
				}

			}
		}

		return $serialized;
	}

	public function update() {

		if ( $this->is_valid() ) {

			if ( $this->instance == null ) {
				$this->instance = $this->QUERY_CLASS::create()->findOneById( $this->raw_data['id'] );
				if ( $this->instance == null ) {
					$this->instance = new $this->MODEL_CLASS();
				}
			}
			foreach ( $this->MODEL_FIELDS as $field ) {

				$fieldname = $field['fieldname'];
				if ( ! key_exists( $fieldname, $this->raw_data ) ) {
					continue;
				}

				if ( $field['setter'] ) {
					if ( $field['setter'] !== 'NOSET' ) {
						$this->instance->{$field['setter']}( $this->raw_data[ $fieldname ] );
					}
				} else {
					$this->instance->{$fieldname} = $this->raw_data[ $fieldname ];
				}

			}
			$this->instance->save();
		}
	}

	public function serialize(): array {
		if ( $this->many ) {
			$serialized = array();

			if ( $this->instance == null ) {
				return array();
			}

			foreach ( $this->instance as $instance ) {
				array_push(
					$serialized,
					$this->__deflateInstance( $instance )
				);
			}

			return $serialized;
		}

		return $this->__deflateInstance( $this->instance );
	}
}