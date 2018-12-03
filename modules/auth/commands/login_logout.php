<?php


include_once __DIR__ . '/commands.php';
include_once __DIR__ . '/../../operations/value_objects/result.php';
include_once __DIR__ . '/../../operations/value_objects/command.php';
include_once __DIR__ . '/../../operations/executor.php';
include_once __DIR__.'/../../../generated/UserQuery.php';
include_once __DIR__.'/../../auth/guard.php';
include_once __DIR__.'/../commands/results/UserResult.php';


global $CMD_LOGIN_USER;
global $CMD_REGISTER_USER;


Executor::getInstance()->registerCommand(
	$CMD_LOGIN_USER,
	new class extends AbstractCommand {
		protected static $minPermissions = array();

		public function execute(?ifcontext $context): AbstractResult {

			$guard = new Guard();
			$user = $guard->authenticate(
				$context->getValue('username'),
				$context->getValue('password')
			);

			if ($user === null) {
				return new UserResult(
					ResultState::OPERATION_ERROR,
					'Username or password invalid!',
					null
				);
			}

			session_start();
			$_SESSION['user'] = $user;

			return new UserResult(
				ResultState::EXECUTED,
				null,
				array($user)
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_LOGOUT_USER,
	new class extends AbstractCommand {
		protected static $minPermissions = array();

		public function execute(?ifcontext $context): AbstractResult {

			session_destroy();

			return new OperationResult(
				ResultState::EXECUTED,
				null
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_REGISTER_USER,
	new class extends AbstractCommand {
		protected static $minPermissions = array();

		public function execute(?ifcontext $context): AbstractResult {

			$user = new User();
			$user->setusername($context->getValue('username'));
			$user->save();
			$guard = new Guard();
			$guard->setPassword($user, $context->getValue('password'));

			session_start();
			$_SESSION['user'] = $user;

			return new UserResult(
				ResultState::EXECUTED,
				null,
				$user
			);
		}
	}
);