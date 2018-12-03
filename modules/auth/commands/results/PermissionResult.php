<?php

include_once __DIR__ . '/../../../operations/value_objects/result.php';


class PermissionResult extends AbstractResult {

	private $permissions;

	public function __construct($state, $message, ?array $permissions=null) {
		parent::__construct($state, $message);
		$this->permissions = $permissions;
	}

	public function getPermissions(): ?array {
		return $this->permissions;
	}

	public function getResultObject() {
		return $this->permissions;
	}
}
