<?php


include_once __DIR__.'/../../common/generic_views/views.php';
include_once __DIR__.'/../../common/value_objects/request.php';
include_once __DIR__.'/../../auth/commands/commands.php';
include_once __DIR__.'/../../operations/executor.php';
include_once __DIR__.'/../serializers/LoginSerializer.php';
include_once __DIR__.'/../../common/generic_views/response/JSONResponse.php';
include_once __DIR__.'/../serializers/UserSerializer.php';


class RegisterView extends AbstractAPIView {

	private function __registerUser($username, $password) {
		global $CMD_REGISTER_USER;
		$ctx = new ExecutionContext();
		$ctx->setValue('username', $username);
		$ctx->setValue('password', $password);
		return Executor::getInstance()->execute($CMD_REGISTER_USER, $ctx);
	}

	public function get( Request $request ): AbstractResponse {
		// TODO: Implement get() method.
	}

	public function post(Request $request): AbstractResponse {

		$serializer = new LoginSerializer($request->postData);
		$result = $this->__registerUser($serializer->getUserName(), $serializer->getPassword());
		$userSerializer = new UserSerializer(null, $result->getUsers()[0]);
		return new JSONResponse(201, $userSerializer->serialize());
	}
}