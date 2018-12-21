<?php

include_once __DIR__.'/value_objects/result.php';


/**
 * Class Executor
 *
 * Autorization and execution instance. Accepts command keys and execution contexts and
 * executes commands, provided the required permissions of the current user are met.
 */
class Executor {

	private static $instance = null;
	private $commands = [];

	/**
	 * Singleton getter.
	 * @return Executor
	 */
	public static function getInstance(): Executor {
		if (Executor::$instance == null) {
			Executor::$instance = new Executor();
		}
		return Executor::$instance;
	}

	/**
	 * Register a command. This method will be called by other components that
	 * need to register commands (anonymous methods) that can be executed under specific permissions.
	 *
	 * @param string $key
	 * @param AbstractCommand $command
	 *
	 * @return bool
	 * @throws \Propel\Runtime\Exception\PropelException
	 */
	public function registerCommand(string $key, AbstractCommand $command): bool {

		if (array_key_exists($key, $this->commands)) {
			return false;
		}

		$this->commands[$key] = $command;
		foreach($command::getMinPermissions() as $permissionKey) {
			$permission = PermissionQuery::create()->filterByKey($permissionKey)->findOneOrCreate();
			$permission->save();
		}
		return true;
	}

	/**
	 * Executes a command.
	 * @param string $key
	 * @param ExecutionContext $context
	 *
	 * @return OperationResult
	 */
	public function execute(string $key, ?ExecutionContext $context): AbstractResult {

		if (!array_key_exists($key, $this->commands)) {
			return new OperationResult(
				ResultState::RUNTIME_ERROR,
				'Command "'.$key.'"" not registered!'
			);
		}

		$commandClass = $this->commands[$key];
		$minPermissions = $commandClass::getMinPermissions();
		if (sizeof($minPermissions) > 0) {
			$guard = new Guard();

			// Check if the requester possesses the required permissions to execute the command.
			// Deny and return otherwise.
			if (!$guard->hasPerms($context->getRequester(), $minPermissions)) {
				return new OperationResult(
					ResultState::INSUFFICIENT_PERMISSIONS
				);
			}
		}

		$command = new $commandClass();
		return $command->execute($context);
	}
}
