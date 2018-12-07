<?php

include_once __DIR__ . '/../../../operations/value_objects/result.php';


class AlarmResult extends AbstractResult {

	private $alarms;

	public function __construct($state, $message, ?array $alarms=null) {
		parent::__construct($state, $message);
		$this->alarms = $alarms;
	}

	public function getUsers(): ?array {
		return $this->alarms;
	}

	public function getResultObject() {
		return $this->alarms;
	}
}
