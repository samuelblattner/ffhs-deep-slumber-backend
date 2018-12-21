<?php

include_once __DIR__ . '/../../../operations/value_objects/result.php';


/**
 * Class UserResult
 *
 * Executor result for Users
 */
class UserResult extends AbstractResult {

	private $users;

	/**
	 * UserResult constructor.
	 *
	 * @param $state: Result state
	 * @param $message: Result message
	 * @param array|null $users: Users
	 */
	public function __construct($state, $message, ?array $users=null) {
		parent::__construct($state, $message);
		$this->users = $users;
	}

	/**
	 * Users getter
	 * @return array|null
	 */
	public function getResultObject() {
		return $this->users;
	}
}
