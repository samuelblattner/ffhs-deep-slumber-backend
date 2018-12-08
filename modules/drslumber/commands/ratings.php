<?php

use Propel\Runtime\ActiveQuery\Criteria;

include_once __DIR__ . '/commands.php';
include_once __DIR__.'/../drslumber.php';
include_once __DIR__.'/results/StatsResult.php';



global $CMD_RATE_LAST_WAKE_UP;
global $CMD_CHECK_RATING_REQUIRED;


Executor::getInstance()->registerCommand(
	$CMD_RATE_LAST_WAKE_UP,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-rate-own-wake-up'
		);

		public function execute(?ifcontext $context): AbstractResult {

			$userDevice = DeviceQuery::create()->filterByUser($context->getRequester())->findOne();

			$cycle = SleepCycleQuery::create()->filterByDevice($userDevice)->orderById(Criteria::DESC)->find()[0];
			$cycle->setRating($context->getValue('rating'));
			$cycle->save();

			return new OperationResult(
				ResultState::EXECUTED,
				null
			);
		}
	}
);



Executor::getInstance()->registerCommand(
	$CMD_CHECK_RATING_REQUIRED,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-check-rating-required'
		);

		public function execute(?ifcontext $context): AbstractResult {

			$userDevice = DeviceQuery::create()->filterByUser($context->getRequester())->findOne();
			$cycle = $userDevice ? SleepCycleQuery::create()->filterByDevice($userDevice)->orderById(Criteria::DESC)->find()[0] : null;

			return new BooleanResult(
				ResultState::EXECUTED,
				null,
				$cycle && $cycle->getRating() == null
			);
		}
	}
);
