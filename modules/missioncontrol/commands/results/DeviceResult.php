<?php

include_once __DIR__ . '/../../../operations/value_objects/result.php';


class DeviceResult extends AbstractResult {

	private $devices;

	public function __construct($state, $message, ?array $devices=null) {
		parent::__construct($state, $message);
		$this->devices = $devices;
	}

	public function getDevices(): ?array {
		return $this->devices;
	}

	public function getResultObject() {
		return $this->devices;
	}
}
