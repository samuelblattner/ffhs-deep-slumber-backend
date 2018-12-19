<?php

namespace MissionControl;

use EventType;
use MessageType;

include __DIR__ . '/../enums.php';


abstract class AbstractMessage {

	protected static $_fields = [];

	protected $_msgType;

	public function deserialize(string $raw) {

		$obj = json_decode($raw, TRUE);

		foreach ($this::$_fields as $field) {
			if ($obj[$field]) {
				$this->{$field} = $obj[$field];
			}
		}
	}

	public function serialize(): string {

		$obj = array();

		foreach ($this::$_fields as $field) {
			if ($this->{$field}) {
				$obj[$field] = $this->{$field};
			}
		}

		$obj['msgType'] = $this->_msgType;

		return json_encode($obj);
	}
}


class Event extends AbstractMessage {

	public $event_type;
	public $timestamp;
	public $value;

	protected $_msgType = MessageType::EVENT;

	protected static $_fields = [
		'event_type',
		'timestamp',
		'value'
	];

}

class Settings extends AbstractMessage {

	public $deviceId;
	public $wakeTime;
	public $wakeMaxSpan;
	public $wakeOffsetEstimator;
	public $accSensitivity;
	public $gyrSensitivity;
	public $irSensitivity;
	public $dataDensity;

	protected $_msgType = MessageType::SETTINGS;

	protected static $_fields = [
		'deviceId',
		'wakeTime',
		'wakeMaxSpan',
		'wakeOffsetEstimator',
		'accSensitivity',
		'gyrSensitivity',
		'irSensitivity',
		'dataDensity',
	];
}

class HelloMessage extends AbstractMessage {

	protected static $_fields = ['hwid'];

	protected $_msgType = MessageType::HELLO;
	protected $hwid = -1;

	public function getHwId(): string {
		return $this->hwid;
	}
}