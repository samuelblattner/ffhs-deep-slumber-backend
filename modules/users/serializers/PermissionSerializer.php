<?php

include_once __DIR__.'/../../common/serializers/serializer.php';


class PermissionSerializer extends AbstractModelSerializer {

	private static $FIELD_ID = 'id';
	private static $FIELD_KEY = 'key';
	private static $FIELD_LABEL = 'label';

	protected $MODEL_FIELDS = [
		[ 'fieldname' => 'id', 'getter' => 'getId' ],
		[ 'fieldname' => 'key', 'getter' => 'getKey'],
		[ 'fieldname' => 'label', 'getter' => 'getLabel'],
		[ 'fieldname' => 'active']
	];
	protected $MODEL_CLASS = Permission::class;

	public function __construct( $raw_data, $instance=null, $many=false) {
		parent::__construct( $raw_data, $instance, $many );
		$this->REQUIRED_FIELDS = [
			PermissionSerializer::$FIELD_ID,
			PermissionSerializer::$FIELD_KEY
		];
	}

	public function asContext($ctx=null): ?ExecutionContext {

		if (!$this->is_valid()) {
			return null;
		}

		if ($ctx == null) {
			$ctx = new ExecutionContext();
		}
		$ctx->setValue('password', $this->instance ? $this->instance->getPassword() : $this->raw_data[UserSerializer::$FIELD_PASSWORD]);
		return $ctx;
	}
}
