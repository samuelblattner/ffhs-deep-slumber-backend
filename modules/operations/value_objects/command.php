<?php

include_once __DIR__.'/../value_objects/command.php';


interface ifContext {

	public function getRequester();
	public function setValue($key, $value);
	public function getValue($key);
}

class ExecutionContext implements ifContext {

	private $requester;
	private $values = array();

	public function __construct($requester=null) {
		$this->requester = $requester;
	}

	public function getRequester() {
		return $this->requester;
	}

	public function setValue($key, $value) {

		$this->values[$key] = $value;
	}

	public function getValue($key) {
		return $this->values[$key];
	}
}


abstract class AbstractCommand {

	protected static $minPermissions = [];
	private $context;

	public static function getMinPermissions() {
		return static::$minPermissions;
	}

	public abstract function execute(?ifContext $context): AbstractResult;
}