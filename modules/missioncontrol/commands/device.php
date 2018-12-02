<?php


include_once __DIR__ . '/commands.php';
include_once __DIR__ . '/results/DeviceResult.php';


global $CMD_LIST_DEVICES;


Executor::getInstance()->registerCommand(
	$CMD_LIST_DEVICES,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
//			'can-list-all-devices',
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$requester  = $context->getRequester();
			$serializer = $context->getValue( 'serializer' );

			$items = array();

			foreach (DeviceQuery::create()->find() as $device) {
				array_push($items, $device);
			}

			return new DeviceResult(
				ResultState::EXECUTED,
				null,
				$items
			);
		}
	}
);

