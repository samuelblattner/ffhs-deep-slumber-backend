<?php

include_once __DIR__ . '/../../../operations/value_objects/result.php';


/**
 * Class PermissionResult
 *
 * Executor result class for permissions.
 */
class PermissionResult extends AbstractResult {

	private $permissions;

	/**
	 * PermissionResult constructor.
	 *
	 * @param $state: Result state
	 * @param $message: Result message
	 * @param array|null $permissions: Returned permissions
	 */
	public function __construct($state, $message, ?array $permissions=null) {
		parent::__construct($state, $message);
		$this->permissions = $permissions;
	}

	/**
	 * Permission getter
	 * @return array|null
	 */
	public function getResultObject() {
		return $this->permissions;
	}
}
