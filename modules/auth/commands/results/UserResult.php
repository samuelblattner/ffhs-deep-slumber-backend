<?php

include_once __DIR__ . '/../../../operations/value_objects/result.php';


class UserResult extends AbstractResult {

	private $users;

	public function __construct($state, $message, ?array $users=null) {
		parent::__construct($state, $message);
		$this->users = $users;
	}

	public function getUsers(): ?array {
		return $this->users;
	}

	public function getResultObject() {
		return $this->users;
	}
}
