<?php

use Propel\Runtime\ActiveQuery\Criteria;

include_once __DIR__ . '/../../../generated/AlarmQuery.php';
include_once __DIR__ . '/results/AlarmResult.php';

include_once __DIR__ . '/commands.php';
include_once __DIR__ . '/../drslumber.php';
include_once __DIR__ . '/results/StatsResult.php';


global $CMD_RATE_LAST_WAKE_UP;
global $CMD_CHECK_RATING_REQUIRED;
global $CMD_LIST_ALARM;
global $CMD_SAVE_ALARM;


Executor::getInstance()->registerCommand(
	$CMD_RATE_LAST_WAKE_UP,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-rate-own-wake-up'
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$userDevice = DeviceQuery::create()->filterByUser( $context->getRequester() )->findOne();
			$cycle      = SleepCycleQuery::create()->filterByDevice( $userDevice )->orderById( Criteria::DESC )->find()[0];
			$cycle->setRating( $context->getValue( 'rating' ) );
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

		public function execute( ?ifcontext $context ): AbstractResult {

			$userDevice = DeviceQuery::create()->filterByUser( $context->getRequester() )->findOne();
			$cycle      = SleepCycleQuery::create()->filterByDevice( $userDevice )->orderById( Criteria::DESC )->find()[0];

			return new BooleanResult(
				ResultState::EXECUTED,
				null,
				$cycle->getRating() == null
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_LIST_ALARM,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-list-own-alarm'
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$userDevice = DeviceQuery::create()->filterByUser( $context->getRequester() )->findOne();
			$alarm      = $userDevice ? AlarmQuery::create()->filterByDevice( $userDevice )->orderById( Criteria::DESC )->find()[0] : null;

			return new AlarmResult(
				ResultState::EXECUTED,
				null,
				$alarm ? array( $alarm ) : null
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_SAVE_ALARM,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-update-own-alarm'
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$requester       = $context->getRequester();
			$alarmSerializer = $context->getValue( 'serializer' );
			$userDevice = DeviceQuery::create()->filterByUser( $requester)->findOne();


			$alarmSerializer->setRawDataField('device_hwid', $userDevice->getHwid());
			$alarmSerializer->update();

			return new AlarmResult(
				ResultState::EXECUTED,
				null,
				array( $alarmSerializer->getInstance() )
			);
		}
	}
);
