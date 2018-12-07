<?php

include_once __DIR__.'/commands.php';
include_once __DIR__.'/../drslumber.php';
include_once __DIR__.'/results/StatsResult.php';



global $CMD_DISPLAY_GLOBAL_STATSTICS;


Executor::getInstance()->registerCommand(
	$CMD_DISPLAY_GLOBAL_STATSTICS,
	new class extends AbstractCommand {
		protected static $minPermissions = array();

		public function execute(?ifcontext $context): AbstractResult {

			$stats = DrSlumber::getGlobalStatistics();

			return new StatsResult(
				ResultState::EXECUTED,
				null,
				$stats
			);
		}
	}
);
