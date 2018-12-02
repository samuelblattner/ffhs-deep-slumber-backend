<?php


include_once __DIR__ . '/commands.php';
include_once __DIR__ . '/../../operations/value_objects/result.php';
include_once __DIR__ . '/../../operations/value_objects/command.php';
include_once __DIR__ . '/../../operations/executor.php';
include_once __DIR__ . '/../../../generated/UserQuery.php';
include_once __DIR__ . '/../../auth/guard.php';
include_once __DIR__ . '/../../auth/commands/results/UserResult.php';

global $CMD_UPDATE_USER;
global $CMD_REGISTER_USER;
global $CMD_DELETE_USER;
global $CMD_CHECK_USERNAME_EXISTS;


Executor::getInstance()->registerCommand(
	$CMD_UPDATE_USER,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-update-same-user',
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$requester  = $context->getRequester();
			$serializer = $context->getValue( 'serializer' );

			if ( $serializer->getId() === $requester->getId() ) {
				$user = UserQuery::create()->findOneById( $serializer->getId() );
				$user->setfirst_name();
			} else {
				return new OperationResult(
					ResultState::INSUFFICIENT_PERMISSIONS,
					null
				);
			}

			return new DeviceResult(
				ResultState::EXECUTED,
				null,
				$user
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_REGISTER_USER,
	new class extends AbstractCommand {
		protected static $minPermissions = array();

		public function execute( ?ifcontext $context ): AbstractResult {

			echo 'hurrli';
			$user = new User();
			$user->setusername( $context->getValue( 'username' ) );
			$user->save();
			$guard = new Guard();
			$guard->setPassword( $user, $context->getValue( 'password' ) );

				echo 'jup';
			return new DeviceResult(
				ResultState::EXECUTED,
				null,
				$user
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_CHECK_USERNAME_EXISTS,
	new class extends AbstractCommand {
		protected static $minPermissions = array();

		public function execute( ?ifcontext $context ): AbstractResult {

			$user = UserQuery::create()->findOneByusername($context->getValue( 'username' ));
			echo $user;
			if ($user == null) {
				return new OperationResult(
					ResultState::OPERATION_ERROR,
					'Username does not exist'
				);
			}
			return new OperationResult(
				ResultState::EXECUTED
			);
		}
	}
);
