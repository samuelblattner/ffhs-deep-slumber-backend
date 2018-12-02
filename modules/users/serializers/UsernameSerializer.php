<?php

include_once __DIR__.'/../../common/serializers/serializer.php';


class UsernameSerializer extends AbstractSerializer {

	private static $FIELD_USERNAME = 'username';

	public function __construct( $raw_data, $instance=null ) {
		parent::__construct( $raw_data, $instance );
		$this->REQUIRED_FIELDS = [
			UsernameSerializer::$FIELD_USERNAME,
		];
	}

	public function getUserName(): ?string {
		if ($this->is_valid()) {
			return $this->raw_data[UsernameSerializer::$FIELD_USERNAME];
		}
		return null;
	}

	public function serialize(): array {
		return $this->raw_data;
	}
}
