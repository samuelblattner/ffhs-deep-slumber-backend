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


/**
 * COMMAND: Execute User-Login and create session.
 */
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

			// If authentication is not successful, return error
			if ($user === null) {
				return new UserResult(
					ResultState::OPERATION_ERROR,
					'Username or password invalid!',
					null
				);
			}

			// If authentication is successful, create a new session and store
			// the session_id on the user instance for later reference
			session_start();
			$user->setSession(session_id());
			$user->save();
			$_SESSION['user'] = $user;

			// Return user as exeuction result
			return new UserResult(
				ResultState::EXECUTED,
				null,
				array($user)
			);
		}
	}
);


/**
 * COMMAND: Execute User-Logout and destroy session.
 */
Executor::getInstance()->registerCommand(
	$CMD_LOGOUT_USER,
	new class extends AbstractCommand {

		protected static $minPermissions = array();

		public function execute(?ifcontext $context): AbstractResult {

			$guard = new Guard();
			$user = $guard->getSessionUser();
			$guard->logout($user);

			return new OperationResult(
				ResultState::EXECUTED,
				null
			);
		}
	}
);


/**
 * COMMAND: Execute user registration.
 */
Executor::getInstance()->registerCommand(
	$CMD_REGISTER_USER,
	new class extends AbstractCommand {

		protected static $minPermissions = array();

		public function execute(?ifcontext $context): AbstractResult {

			// Create user instance
			$user = new User();
			$user->setusername($context->getValue('username'));
			$user->save();
			$guard = new Guard();
			$guard->setPassword($user, $context->getValue('password'));

			// Set default permissions
			$guard->addPermissions(
				$user,
				PermissionQuery::create()->filterByKey(
					array(
						'can-update-same-user',
						'can-add-device-to-own-user',
						'can-list-own-devices',
						'can-delete-same-user',
						'can-rate-own-wake-up',
						'can-check-rating-required',
						'can-list-own-alarm',
						'can-update-own-alarm',
						'can-list-own-sleep-statistics'
					)
				)
			);

			// Create a new session and store with the user
			session_start();
			$user->setSession(session_id());
			$user->save();
			$_SESSION['user'] = $user;

			// Return user as execution result.
			return new UserResult(
				ResultState::EXECUTED,
				null,
				array($user)
			);
		}
	}
);
