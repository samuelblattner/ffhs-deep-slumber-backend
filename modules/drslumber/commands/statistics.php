<?php

include_once __DIR__.'/commands.php';
include_once __DIR__.'/../drslumber.php';
include_once __DIR__.'/results/StatsResult.php';



global $CMD_DISPLAY_GLOBAL_STATSTICS;
global $CMD_DISPLAY_USER_STATSTICS;
global $CMD_DISPLAY_USER_LAST_SLEEP_PROFILE;


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


Executor::getInstance()->registerCommand(
	$CMD_DISPLAY_USER_STATSTICS,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-list-own-sleep-statistics'
		);

		public function execute(?ifcontext $context): AbstractResult {

			$stats = DrSlumber::getUserStatistics($context->getRequester());

			return new StatsResult(
				ResultState::EXECUTED,
				null,
				$stats
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_DISPLAY_USER_LAST_SLEEP_PROFILE,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-list-own-sleep-events'
		);

		public function execute(?ifcontext $context): AbstractResult {

			$stats = DrSlumber::getUserLastSleepProfile($context->getRequester());

			return new StatsResult(
				ResultState::EXECUTED,
				null,
				$stats
			);
		}
	}
);
