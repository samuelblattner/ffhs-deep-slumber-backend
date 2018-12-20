<?php

include_once __DIR__.'/../commands/commands.php';


class WorldAverageSleepHoursView extends AbstractAPIView {

	public function get(Request $request): AbstractResponse {

		global $CMD_DISPLAY_GLOBAL_STATSTICS;
		$res = Executor::getInstance()->execute($CMD_DISPLAY_GLOBAL_STATSTICS, new ExecutionContext());

		return new JSONResponse(200, $res->getResultObject());
	}
}

class UserSleepStatisticsView extends AbstractAPIView {

	public function get(Request $request): AbstractResponse {

		global $CMD_DISPLAY_USER_STATSTICS;
		$res = Executor::getInstance()->execute($CMD_DISPLAY_USER_STATSTICS, $this->getExecutionContext($request));

		if ($res->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();
		}

		return new JSONResponse(200, $res->getResultObject());

	}
}

class UserLastSleepProfileView extends AbstractAPIView {

	public function get(Request $request): AbstractResponse {

		global $CMD_DISPLAY_USER_LAST_SLEEP_PROFILE;
		$res = Executor::getInstance()->execute($CMD_DISPLAY_USER_LAST_SLEEP_PROFILE, $this->getExecutionContext($request));

		if ($res->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();
		}

		return new JSONResponse(200, $res->getResultObject());
	}
}
