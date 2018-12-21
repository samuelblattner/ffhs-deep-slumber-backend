<?php

include_once __DIR__.'/abstract.php';
include_once __DIR__.'/response/RawFileResponse.php';
include_once __DIR__.'/response/JSONResponse.php';
include_once __DIR__.'/../../users/serializers/UserSerializer.php';


/**
 * Class ListAPIView
 *
 * Generic View to list instances of a given model.
 */
abstract class ListAPIView extends AbstractModelAPIView {

	/**
	 * Retrieve the list of instances.
	 * Every implementing concrete class will implement the getCommandKey-method.
	 * This method will return the command key required to retrieve the specific model instances via Executor.
	 * @param ExecutionContext $ctx
	 *
	 * @return AbstractResult|OperationResult
	 */
	public function __retrieve(ExecutionContext $ctx) {
		return Executor::getInstance()->execute($this->getCommandKey(), $ctx);
	}

	/**
	 * Main entry point for GET-requests.
	 * @param Request $request
	 *
	 * @return AbstractResponse
	 */
	public function get( Request $request ): AbstractResponse {

		$result = $this->__retrieve($this->getExecutionContext($request));

		/**
		 * Error handling. If command, i.e. instance list retrieval was successful,
		 * the list will be serialized and returned as JSON-response.
		 * If user has insufficient permissions or a server error occurred, react accordingly.
		 */
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


/**
 * Class UpdateAPIView
 *
 * Generic View to update an instance of a given model.
 */
abstract class UpdateAPIView extends AbstractModelAPIView {

	/**
	 * Update an instance.
	 * @param $user
	 * @param $rawData
	 *
	 * @return AbstractResult|OperationResult
	 */
	private function __update($user, $rawData) {
		$ctx = new ExecutionContext($user);
		$serializer_class = $this->getSerializer();
		$serializer = new $serializer_class($rawData);
		$ctx = $serializer->asContext($ctx);
		$ctx->setValue('serializer', $serializer);
		return Executor::getInstance()->execute($this->getCommandKey(), $ctx);
	}

	/**
	 * Main entry point for PATCH-requests.
	 * @param Request $request
	 *
	 * @return AbstractResponse
	 */
	public function patch( Request $request ): AbstractResponse {
		$guard = new Guard();
		$user = $guard->getSessionUser();
		$result = $this->__update($user, $this->getRequestData($request));


		/**
		 * Error handling. If command, i.e. update is successful, return serialized instance.
		 * Otherwise return error message.
		 */
		if ($result->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();

		}

		$serializer_class = $this->getSerializer();
		$serializer = new $serializer_class(null, $result->getResultObject()[0]);
		return new JSONResponse(200, $serializer->serialize());
	}
}


/**
 * Class DeleteAPIView
 *
 * Generic deletion view
 */
abstract class DeleteAPIView extends AbstractModelAPIView {

	private function __delete($user, $rawData) {
		$ctx = new ExecutionContext($user);
		$serializer_class = $this->getSerializer();
		$serializer = new $serializer_class($rawData);
		$ctx = $serializer->asContext($ctx);
		return Executor::getInstance()->execute($this->getCommandKey(), $ctx);

	}

	public function delete( Request $request ): AbstractResponse {
		$guard = new Guard();
		$user = $guard->getSessionUser();
		$result = $this->__delete($user, $this->getRequestData($request));

		if ($result->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();
		}

		return new JSONResponse(204, null);
	}
}


/**
 * Class NotFoundView
 *
 * 404 error view
 */
class NotFoundView extends  AbstractAPIView {
	public function get(Request $request): AbstractResponse {
		return new NotFoundResponse();
	}
}


/**
 * Class RawFileView
 *
 * Return raw files such as assets (js, jpg, etc...)
 */
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

/**
 * Class FrontendView
 *
 * Return template.
 */
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

	/**
	 * Render JS-context to pass init-objects to javascript-world.
	 *
	 * @param $ctx
	 *
	 * @return string
	 */
	private function __renderJSContext($ctx) {
		$out = '<script>window.ctx = {}; ';

		foreach($ctx as $key => $value) {
			$out .= 'window.ctx.' . $key . ' = ' . (is_object($value) || is_array($value) ? json_encode($value) : $value) . '; ';
		}
		return $out . '</script>';
	}

	/**
	 * @return bool|mixed|null|string
	 */
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


/**
 * Class CreateAPIView
 *
 * Currently not required.
 */
abstract class CreateAPIView extends AbstractModelAPIView {

	public function post(Request $request): AbstractResponse {}
}