<?php


abstract class ResultState {

	const EXECUTED = 1;
	const RUNTIME_ERROR = 2;
	const INSUFFICIENT_PERMISSIONS = 4;
	const OPERATION_ERROR = 8;
}


class ResultMeta {

	private $resultState;
	private $resultMessage;

	public function __construct(int $state, ?String $message=null) {
		$this->resultState = $state;
		$this->resultMessage = $message;
	}

	public function getState(): int {
		return $this->resultState;
	}

	public function getMessage(): ?String {
		return $this->resultMessage;
	}
}


abstract class AbstractResult {

	private $resultMeta;

	public function __construct(int $state, ?String $message=null) {
		$this->resultMeta = new ResultMeta($state, $message);
	}

	public function getResultMeta(): ResultMeta {
		return $this->resultMeta;
	}

	public function getResultObject() {
		return null;
	}
}


class OperationResult extends AbstractResult {

}


class BooleanResult extends AbstractResult {

	private $yesno;

	public function __construct( int $state, ?String $message = null, bool $yesno = false) {
		parent::__construct( $state, $message );
		$this->yesno = $yesno;
	}

	public function getResultObject() {
		return $this->yesno;
	}
}
