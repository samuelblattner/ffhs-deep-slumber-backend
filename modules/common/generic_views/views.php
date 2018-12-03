<?php

include_once __DIR__.'/abstract.php';
include_once __DIR__.'/response/RawFileResponse.php';
include_once __DIR__.'/response/JSONResponse.php';
include_once __DIR__.'/../../users/serializers/UserSerializer.php';


abstract class ListAPIView extends AbstractModelAPIView {

	public function __retrieve($ctx) {
		return Executor::getInstance()->execute($this->getCommandKey(), $ctx);
	}

	public function get( Request $request ): AbstractResponse {

		$result = $this->__retrieve($this->getExecutionContext($request));

		if ($result->getResultMeta()->getState() == ResultState::EXECUTED) {
			$serializerClass = $this->getSerializer();
			$serializer = new $serializerClass(null, $result->getResultObject(), true);
			return new JSONResponse(200, $serializer->serialize());
		} else if ($result->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();
		} else {
			return new ServerErrorResponse();
		}

	}
}


abstract class CreateAPIView extends AbstractModelAPIView {

	public function post(Request $request): AbstractResponse {

	}

}


abstract class UpdateAPIView extends AbstractModelAPIView {

	private function __update($user, $rawData) {
		$ctx = new ExecutionContext($user);
		$serializer_class = $this->getSerializer();
		$serializer = new $serializer_class($rawData);
		$ctx = $serializer->asContext($ctx);
		return Executor::getInstance()->execute($this->getCommandKey(), $ctx);

	}

	public function patch( Request $request ): AbstractResponse {
		$guard = new Guard();
		$user = $guard->getSessionUser();
		$result = $this->__update($user, $this->getRequestData($request));

		if ($result->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();
		}
	}
}


abstract class DeleteAPIView extends AbstractModelAPIView {

}


class NotFoundView extends  AbstractAPIView {
	public function get(Request $request): AbstractResponse {
		return new NotFoundResponse();
	}
}

class RawFileView extends  AbstractAPIView {

	private $filePath;
	private $contentType;

	public function __construct($filePath, $contentType) {
		parent::__construct();
		$this->filePath = $filePath;
		$this->contentType = $contentType;
	}

	public function get( Request $request ): AbstractResponse {
		return new RawFileResponse(200, file_get_contents($this->filePath), $this->contentType);
	}
}

class FrontendView extends AbstractAPIView {


	private function __getContext() {
		$guard = new Guard();
		$user = $guard->getSessionUser();
		$serializer = new UserSerializer(null, $user);

		$ctx = array();
		if($user != null) {
			$ctx['user'] = $serializer->serialize();
		}
		return $this->__renderJSContext($ctx);
	}

	private function __renderJSContext($ctx) {
		$out = '<script>window.ctx = {}; ';

		foreach($ctx as $key => $value) {
			$out .= 'window.ctx.' . $key . ' = ' . (is_object($value) || is_array($value) ? json_encode($value) : $value) . '; ';
		}
		return $out . '</script>';
	}

	private function __getFrontendEntryWithContext() {
		global $SETTINGS;

		if (file_exists($SETTINGS['frontend-entry-path'])) {
			$frontendContents = file_get_contents( $SETTINGS['frontend-entry-path'] );
			$frontendContents = str_replace('<!-- CONTEXT -->', $this->__getContext(), $frontendContents);
			return $frontendContents;
		}
		return null;

	}

	public function get( Request $request): AbstractResponse {




		$frontend = $this->__getFrontendEntryWithContext();
		if ($frontend != null) {
			return new RawFileResponse( 200, $frontend, 'text/html' );
		}
		return new NotFoundResponse();
	}
}