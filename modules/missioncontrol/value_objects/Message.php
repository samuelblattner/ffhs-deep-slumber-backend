<?php

namespace MissionControl;

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
}


class Event extends AbstractMessage {

	private $eventType;
	private $timestamp;
	private $value;

	protected $_msgType = MessageType::EVENT;
}

class Settings extends AbstractMessage {

	private $wakeTime;
	private $wakeMaxSpan;
	private $wakeOffsetEstimator;
	private $accSensitivity;
	private $gyrSensitivity;
	private $irSensitivity;
	private $dataDensity;

	protected $_msgType = MessageType::SETTINGS;

}

class HelloMessage extends AbstractMessage {

	protected static $_fields = ['hwid'];

	protected $_msgType = MessageType::HELLO;
	protected $hwid = -1;

	public function getHwId(): string {
		return $this->hwid;
	}
}