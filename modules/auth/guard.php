<?php

use Propel\Runtime\ActiveQuery\Criteria;

class Guard {

	/**
	 * Main Authentication Method.
	 * Returns a User-Object if successful, null if not.
	 * @param string $username
	 * @param string $password
	 *
	 * @return null|User
	 */
	public function authenticate(string $username, string $password): ?User {

		$user = UserQuery::create()->findOneByusername($username);

		if ($user && password_verify($password, $user->getpassword())) {
			return $user;
		}
		return null;
	}

	public function getSessionUser(): ?User {
		session_start();
		return $_SESSION['user'];
	}

	public function setPassword(User $user, string $password): bool {
		$user->setHashedPassword($password);
		$user->save();
		return true;
	}

	public function logout(User $user): bool {
		session_destroy();
		return true;
	}

	public function hasPerms(?AbstractCitizen $citizen, $permissions): bool {
		if ($citizen == null) {
			return false;
		}
		$citizen->clearPermissions();
		$citizenPermissions = $citizen->getPermissions()->getColumnValues('key');

		foreach ($permissions as $permission) {
			if (!in_array($permission, $citizenPermissions)) {
				return false;
			}
		}

		return true;
	}

	public function addPermissions(AbstractCitizen $citizen, $permissions): bool {
		foreach($permissions as $permission) {
			$citizen->addPermission($permission);
			$citizen->save();
			$permission->save();
		}
		return true;
	}
}