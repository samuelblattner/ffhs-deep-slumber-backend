<?php

include_once __DIR__ . '/../../../operations/value_objects/result.php';


class UserResult extends AbstractResult {

	private $user;

	public function __construct($state, $message, ?User $user=null) {
		parent::__construct($state, $message);
		$this->user = $user;
	}

	public function getUser(): User {
		return $this->user;
	}
}
