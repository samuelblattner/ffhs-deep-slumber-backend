<?php

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
		$user->setpassword(password_hash($password, PASSWORD_DEFAULT));
		$user->save();
		return true;
	}

	public function logout(User $user): bool {

	}

	public function hasPerms(AbstractCitizen $citizen, $permissions): bool {

	}

	public function addPermissions(AbstractCitizen $citizen, $permissions): bool {

	}
}