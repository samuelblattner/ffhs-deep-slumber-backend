<?php

include __DIR__.'/response/GenericResponses.php';


abstract class AbstractAPIView {

	protected $urlArgs = null;

	public function __construct(?array $urlArgs=null) {
		$this->urlArgs = $urlArgs;
	}

	public function get(Request $request): AbstractResponse {
		return new MethodNotAllowedResponse();
	}

	public function post(Request $request): AbstractResponse {
		return new MethodNotAllowedResponse();
	}

	public function patch(Request $request): AbstractResponse {
		return new MethodNotAllowedResponse();
	}

	public function delete(Request $request): AbstractResponse {
		return new MethodNotAllowedResponse();
	}

	public function getRequestData($request): array {
		return $request->postData ? $request->postData : array();
	}

	public function getExecutionContext($request): ExecutionContext {
		$guard = new Guard();
		$ctx = new ExecutionContext($guard->getSessionUser());
		return $ctx;
	}

}

abstract class AbstractModelAPIView extends AbstractAPIView {

	protected abstract function getCommandKey(): string;
	protected abstract function getSerializer();
}
