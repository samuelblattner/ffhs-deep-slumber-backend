<?php

include_once __DIR__.'/value_objects/result.php';


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
	 * Registers a new command for a given key.
	 * @param string $key
	 * @param AbstractCommand $command
	 *
	 * @return bool
	 */
	public function registerCommand(string $key, AbstractCommand $command): bool {

		if (array_key_exists($key, $this->commands)) {
			return false;
		}

		$this->commands[$key] = $command;
		return true;
	}

	/**
	 * Executes a command.
	 * @param string $key
	 * @param AbstractContext $context
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