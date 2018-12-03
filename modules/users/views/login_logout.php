<?php


include_once __DIR__.'/../../common/generic_views/views.php';
include_once __DIR__.'/../../common/value_objects/request.php';
include_once __DIR__.'/../../auth/commands/commands.php';
include_once __DIR__.'/../../operations/executor.php';
include_once __DIR__.'/../serializers/LoginSerializer.php';
include_once __DIR__.'/../../common/generic_views/response/JSONResponse.php';
include_once __DIR__.'/../serializers/UserSerializer.php';


class LoginView extends AbstractAPIView {

	private function attemptLogin($username, $password): UserResult {
		global $CMD_LOGIN_USER;
		$ctx = new ExecutionContext();
		$ctx->setValue('username', $username);
		$ctx->setValue('password', $password);
		return Executor::getInstance()->execute($CMD_LOGIN_USER, $ctx);
	}

	public function get( Request $request ): AbstractResponse {
		return new JSONResponse(200);
	}

	public function post(Request $request): AbstractResponse {
		$serializer = new LoginSerializer($request->postData);
		if (!$serializer->is_valid()) {
			return new BadRequestResponse();
		}
		$result = $this->attemptLogin($serializer->getUserName(), $serializer->getPassword());

		if ($result->getResultMeta()->getState() == ResultState::OPERATION_ERROR) {
			return new UnauthorizedResponse(401, $result->getResultMeta()->getMessage());
		}
		$userSerializer = new UserSerializer(null, $result->getUsers()[0]);
		return new JSONResponse(200, $userSerializer->serialize());
	}
}

class LogoutView extends AbstractAPIView {

    public function post( Request $request ): AbstractResponse {
    	global $CMD_LOGOUT_USER;

	    $guard = new Guard();
	    $user = $guard->getSessionUser();

	    if ($user === null) {
		    return new UnauthorizedResponse();
	    }
	    $result = Executor::getInstance()->execute($CMD_LOGOUT_USER, null);

	    return new JSONResponse(200, null);
    }
}
