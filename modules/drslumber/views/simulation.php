<?php

include_once __DIR__.'/../commands/commands.php';


class GenerateSleepCycleView extends AbstractAPIView {


	public function post(Request $request): AbstractResponse {

		global $CMD_GENERATE_RANDOM_SLEEP_CYCLE;

		$ctx = $this->getExecutionContext($request);
		$ctx->setValue('userId', $this->getRequestData($request)['userId']);
     	$res = Executor::getInstance()->execute($CMD_GENERATE_RANDOM_SLEEP_CYCLE, $ctx);

		if ($res->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();
		}

		return new JSONResponse(200, $res->getResultObject());
	}
}
