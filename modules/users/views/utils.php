<?php


include_once __DIR__ . '/../../common/generic_views/views.php';
include_once __DIR__ . '/../../common/value_objects/request.php';
include_once __DIR__ . '/../../users/commands/commands.php';
include_once __DIR__ . '/../../operations/executor.php';
include_once __DIR__ . '/../serializers/LoginSerializer.php';
include_once __DIR__ . '/../../common/generic_views/response/JSONResponse.php';
include_once __DIR__ . '/../serializers/UserNameSerializer.php';


class CheckUserNameView extends AbstractAPIView {

	private function __checkUserNameExists( $username ) {
		global $CMD_CHECK_USERNAME_EXISTS;
		$ctx = new ExecutionContext();
		$ctx->setValue( 'username', $username );

		return Executor::getInstance()->execute( $CMD_CHECK_USERNAME_EXISTS, $ctx );
	}

	public function get( Request $request ): AbstractResponse {
		$serializer = new UsernameSerializer( $request->getData );

		$result = $this->__checkUserNameExists( $serializer->getUserName() );

		$responseData = array(
			[
				'available' => false
			]
		);


		if ( $result->getResultMeta()->getState() == ResultState::OPERATION_ERROR ) {
			$responseData['available'] = true;
		}

		return new JSONResponse( 200, $responseData );
	}
}