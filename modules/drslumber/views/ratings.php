<?php

include_once __DIR__.'/../commands/commands.php';


class RateLastWakeUpView extends AbstractAPIView {


	public function post(Request $request): AbstractResponse {

		global $CMD_RATE_LAST_WAKE_UP;

		$ctx = $this->getExecutionContext($request);
		$ctx->setValue('rating', $this->getRequestData($request)['rating']);
     	$res = Executor::getInstance()->execute($CMD_RATE_LAST_WAKE_UP, $ctx);

		if ($res->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();
		}

		return new JSONResponse(200, $res->getResultObject());
	}
}


class RatingRequiredView extends AbstractAPIView {


	public function get(Request $request): AbstractResponse {

		global $CMD_CHECK_RATING_REQUIRED;

     	$res = Executor::getInstance()->execute($CMD_CHECK_RATING_REQUIRED, $this->getExecutionContext($request));

		if ($res->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();
		}

		return new JSONResponse(200, $res->getResultObject());
	}
}