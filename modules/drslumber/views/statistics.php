<?php

include_once __DIR__.'/../commands/commands.php';


class WorldAverageSleepHoursView extends AbstractAPIView {


	public function get(Request $request): AbstractResponse {

		global $CMD_DISPLAY_GLOBAL_STATSTICS;
		$res = Executor::getInstance()->execute($CMD_DISPLAY_GLOBAL_STATSTICS, new ExecutionContext());

		return new JSONResponse(200, $res->getResultObject());
	}
}