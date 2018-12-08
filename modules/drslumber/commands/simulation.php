<?php

use Propel\Runtime\ActiveQuery\Criteria;

include_once __DIR__ . '/commands.php';
include_once __DIR__.'/../drslumber.php';
include_once __DIR__.'/results/StatsResult.php';


global $CMD_GENERATE_RANDOM_SLEEP_CYCLE;


Executor::getInstance()->registerCommand(
	$CMD_GENERATE_RANDOM_SLEEP_CYCLE,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-generate-random-sleep-cycles-for-all-users'
		);

		public function execute(?ifcontext $context): AbstractResult {

			$userId = $context->getValue('userId');
			$userDevice = DeviceQuery::create()->filterByUserId($userId)->findOne();

			$cycle = new SleepCycle();
			$start_hr = 19 + random_int(0, 6);
			if ($start_hr >= 24) {
				$start_hr -= 24;
			}
			$end_hr = 5 + random_int(0, 5);

			$cycle->setStart($start_hr.':00');
			$cycle->setStop($end_hr.':00');
			$cycle->setDuration((($end_hr - ($start_hr < $end_hr ? $start_hr : $start_hr - 24)) * 60));
			$cycle->setDevice($userDevice);
			$userDevice->save();
			$cycle->save();

			return new OperationResult(
				ResultState::EXECUTED,
				null
			);
		}
	}
);

