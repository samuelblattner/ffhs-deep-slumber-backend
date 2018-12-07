<?php

include_once __DIR__ . '/../../../operations/value_objects/result.php';


class StatsResult extends AbstractResult {

	private $stats;

	public function __construct($state, $message, ?array $stats=null) {
		parent::__construct($state, $message);
		$this->stats = $stats;
	}

	public function getUsers(): ?array {
		return $this->stats;
	}

	public function getResultObject() {
		return $this->stats;
	}
}
