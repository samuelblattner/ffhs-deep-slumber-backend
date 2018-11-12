<?php

include __DIR__.'/../../common/serializers/serializer.php';


class LoginSerializer extends AbstractSerializer {

	private static $FIELD_USERNAME = 'username';
	private static $FIELD_PASSWORD = 'password';

	public function __construct( $raw_data, $instance=null ) {
		parent::__construct( $raw_data, $instance );
		$this->REQUIRED_FIELDS = [
			LoginSerializer::$FIELD_USERNAME,
			LoginSerializer::$FIELD_PASSWORD
		];
	}

	public function getUserName(): ?string {
		if ($this->is_valid()) {
			return $this->raw_data[LoginSerializer::$FIELD_USERNAME];
		}
		return null;
	}

	public function getPassword(): ?string {
		if ($this->is_valid()) {
			return $this->raw_data[LoginSerializer::$FIELD_PASSWORD];
		}
		return null;
	}

	public function serialize(): array {
		return $this->raw_data;
	}
}
