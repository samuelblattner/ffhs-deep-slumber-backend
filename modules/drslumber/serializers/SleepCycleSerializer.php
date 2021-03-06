<?php

include_once __DIR__.'/../../common/serializers/serializer.php';


class SleepCycleSerializer extends AbstractModelSerializer {

	private static $FIELD_ID = 'id';
	private static $FIELD_START = 'start';
	private static $FIELD_STOP = 'stop';
	private static $FIELD_DURATION = 'duration';
	private static $FIELD_DEVICE_HWID = 'device_hwid';
	private static $RATING = 'rating';

	protected $MODEL_FIELDS = [
		[ 'fieldname' => 'id', 'getter' => 'getId' , 'setter' => 'NOSET'],
		[ 'fieldname' => 'start', 'getter' => 'getStart', 'setter' => 'NOSET'],
		[ 'fieldname' => 'stop', 'getter' => 'getStop', 'setter' => 'NOSET'],
		[ 'fieldname' => 'duration', 'getter' => 'getDuration', 'setter' => 'NOSET'],
		[ 'fieldname' => 'device_hwid', 'getter' => 'getDevicehwid', 'setter' => 'NOSET'],
		[ 'fieldname' => 'rating', 'getter' => 'getRating', 'setter' => 'NOSET']
	];
	protected $MODEL_CLASS = SleepCycle::class;
	protected $QUERY_CLASS = SleepCycleQuery::class;

	public function __construct( $raw_data, $instance=null, $many=false) {
		parent::__construct( $raw_data, $instance, $many );
		$this->REQUIRED_FIELDS = [
			SleepCycleSerializer::$FIELD_ID,
			SleepCycleSerializer::$FIELD_START,
			SleepCycleSerializer::$FIELD_STOP,
			SleepCycleSerializer::$FIELD_DURATION,
			SleepCycleSerializer::$FIELD_DEVICE_HWID,
			SleepCycleSerializer::$RATING,
		];
	}

	public function is_valid(): bool {
		return parent::is_valid(); // TODO: Change the autogenerated stub
	}

	public function asContext($ctx=null): ?ExecutionContext {

		if (!$this->is_valid()) {
			return null;
		}

		if ($ctx == null) {
			$ctx = new ExecutionContext();
		}
		$ctx->setValue('id', $this->instance ? $this->instance->getId() : (int)$this->raw_data[SleepCycleSerializer::$FIELD_ID]);
		$ctx->setValue('earliest', $this->instance ? $this->instance->getEarliest() : (int)$this->raw_data[SleepCycleSerializer::$FIELD_START]);
		$ctx->setValue('latest', $this->instance ? $this->instance->getLatest() : (int)$this->raw_data[SleepCycleSerializer::$FIELD_STOP]);
		$ctx->setValue('active', $this->instance ? $this->instance->getActive() : (int)$this->raw_data[SleepCycleSerializer::$FIELD_DURATION]);
		$ctx->setValue('device_hwid', $this->instance ? $this->instance->getActive() : (int)$this->raw_data[SleepCycleSerializer::$FIELD_DEVICE_HWID]);
		return $ctx;
	}

}
