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

	public $hwid;
	public $event_type;
	public $timestamp;
	public $value;

	protected $_msgType = MessageType::EVENT;

	protected static $_fields = [
		'hwid',
		'event_type',
		'timestamp',
		'value'
	];

}

class Settings extends AbstractMessage {

	public $deviceId;
	public $earliestWakeTime;
	public $latestWakeTime;
	public $wakeMaxSpan;
	public $wakeOffsetEstimator;
	public $accSensitivity;
	public $gyrSensitivity;
	public $irSensitivity;
	public $dataDensity;

	protected $_msgType = MessageType::SETTINGS;

	protected static $_fields = [
		'deviceId',
		'earliestWakeTime',
		'latestWakeTime',
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
	public $hwid = -1;

	public function getHwId(): string {
		return $this->hwid;
	}
}

class GoodbyeMessage extends AbstractMessage {

	protected static $_fields = ['hwid'];

	protected $_msgType = MessageType::GOODBYE;
	public $hwid = -1;

	public function getHwId(): string {
		return $this->hwid;
	}
}

class RequestDeviceStateMessage extends AbstractMessage {

	protected static $_fields = ['hwid'];

	protected $_msgType = MessageType::REQUEST_DEVICE_STATE;
	protected $hwid = -1;

	public function __construct($hwid=null) {
		$this->hwid = $hwid;
	}

	public function getHwId(): string {
		return $this->hwid;
	}
}

class DeviceState extends AbstractMessage {

	protected static $_fields = [''];

	protected $_msgType = MessageType::DEVICE_STATE;

	public $deviceId;
	public $state;
	public $online;
	public $recentEvents;


}