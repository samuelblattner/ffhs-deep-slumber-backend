<?php


include_once __DIR__ . '/commands.php';
include_once __DIR__ . '/../../operations/value_objects/result.php';
include_once __DIR__ . '/../../operations/value_objects/command.php';
include_once __DIR__ . '/../../operations/executor.php';
include_once __DIR__ . '/../../../generated/UserQuery.php';
include_once __DIR__ . '/../../auth/guard.php';
include_once __DIR__ . '/../../auth/commands/results/UserResult.php';
include_once __DIR__ . '/../../auth/commands/results/PermissionResult.php';

global $CMD_UPDATE_USER;
global $CMD_DELETE_USER;
global $CMD_DELETE_OTHER_USER;
global $CMD_REGISTER_USER;
global $CMD_DELETE_USER;
global $CMD_CHECK_USERNAME_EXISTS;
global $CMD_LIST_USER_PERMISSIONS;
global $CMD_TOGGLE_USER_PERMISSION;
global $CMD_ADD_DEVICE;
global $CMD_LIST_USER_DEVICES;
global $CMD_LIST_ALL_USERS_DEVICES;


Executor::getInstance()->registerCommand(
	$CMD_DELETE_USER,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-delete-same-user',
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$user  = $context->getRequester();
			$guard = new Guard();
			$guard->logout($user);
			$user->delete();

			return new UserResult(
				ResultState::EXECUTED,
				null,
				null
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_DELETE_OTHER_USER,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-delete-all-users',
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$requester  = $context->getRequester();
			$userId = $context->getValue( 'id' );

			$user = UserQuery::create()->findOneById( $userId );
			$guard = new Guard();
			$devices = $user->getDevices();
			foreach($devices as $device) {
				$user->removeDevice($device);
				$device->getSleepCycles()->delete();
				$device->save();
			}
			$guard->logout($user);
			$user->delete();

			return new UserResult(
				ResultState::EXECUTED,
				null,
				null
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_UPDATE_USER,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-update-same-user',
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$requester  = $context->getRequester();
			$userId = $context->getValue( 'id' );
			$userSerializer = $context->getValue('serializer');

			if ($userId === $requester->getId() ) {
				$userSerializer->update();

			} else {
				return new OperationResult(
					ResultState::INSUFFICIENT_PERMISSIONS,
					null
				);
			}

			return new UserResult(
				ResultState::EXECUTED,
				null,
				array($userSerializer->getInstance())
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


Executor::getInstance()->registerCommand(
	$CMD_LISTS_USERS,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-list-all-users'
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$users = UserQuery::create()->find()->getArrayCopy();

			return new UserResult(
				ResultState::EXECUTED,
				null,
				$users
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_LIST_USER_PERMISSIONS,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-list-all-users-permissions'
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$allPermissions = PermissionQuery::create()->find()->getArrayCopy();
			$userPermissionKeys = UserQuery::create()->findOneById(
				$context->getValue('forUser')
			)->getPermissions()->getColumnValues('key');

			foreach($allPermissions as $permission) {
				if (in_array($permission->getKey(), $userPermissionKeys)) {
					$permission->active = true;
				}
			}

			return new PermissionResult(
				ResultState::EXECUTED,
				null,
				$allPermissions
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_TOGGLE_USER_PERMISSION,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-edit-all-users-permissions'
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$permission = PermissionQuery::create()->findOneById($context->getValue('permissionId'));
			$user = UserQuery::create()->findOneById($context->getValue('forUser'));
			if ($context->getValue('toggle')) {
				$user->addPermission($permission);
			} else {
				$user->removePermission($permission);

			}
			$user->save();
			$permission->save();

			return new OperationResult(
				ResultState::EXECUTED,
				null
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_ADD_DEVICE,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-add-device-to-own-user'
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$device = DeviceQuery::create()->findOneByHwid($context->getValue('deviceId'));

			if ($device == null) {
				return new OperationResult(
					ResultState::OPERATION_ERROR,
					'No such device'
				);
			}

			if ($device->getUser() != null) {
				return new OperationResult(
					ResultState::OPERATION_ERROR,
					'This device is already in use!'
				);
			}

			$user = UserQuery::create()->findOneById($context->getValue('forUser'));
			$user->addDevice($device);
			$user->save();
			$device->save();

			return new DeviceResult(
				ResultState::EXECUTED,
				null,
				array($device)
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_REMOVE_DEVICE,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-remove-devices-from-all-users'
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$device = DeviceQuery::create()->findOneByHwid($context->getValue('deviceId'));

			if ($device == null) {
				return new OperationResult(
					ResultState::OPERATION_ERROR,
					'No such device'
				);
			}

			$user = UserQuery::create()->findOneById($context->getValue('forUser'));
			$user->removeDevice($device);
			$user->save();

			$device->getSleepCycles()->delete();
			$device->save();

			return new DeviceResult(
				ResultState::EXECUTED,
				null,
				array($device)
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_LIST_USER_DEVICES,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-list-own-devices'
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$user = $context->getRequester();

			$devices = $user->getDevices()->getArrayCopy();

			return new DeviceResult(
				ResultState::EXECUTED,
				null,
				$devices
			);
		}
	}
);


Executor::getInstance()->registerCommand(
	$CMD_LIST_ALL_USERS_DEVICES,
	new class extends AbstractCommand {
		protected static $minPermissions = array(
			'can-list-all-users-devices'
		);

		public function execute( ?ifcontext $context ): AbstractResult {

			$userId = $context->getValue('userId');
			$user = UserQuery::create()->findOneById($userId);

			$devices = $user->getDevices()->getArrayCopy();

			return new DeviceResult(
				ResultState::EXECUTED,
				null,
				$devices
			);
		}
	}
);

