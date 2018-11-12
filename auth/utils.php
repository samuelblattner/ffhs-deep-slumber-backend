<?php
/**
 * Created by PhpStorm.
 * User: samuelblattner
 * Date: 18.08.18
 * Time: 16:17
 */

include __DIR__.'/../config/settings.php';


class PasswordGenerator {

	/**
	 * Return a random ASCII Character.
	 * @return string
	 */
	private static function __getRandomASCIICharacter() {
		return chr(rand(33, 126));
	}

	public static function generatePassword( $length = DEFAULT_PASSWORD_LENGTH ) {

		$pw = '';
		for ( $i = 0; $i < $length; $i ++ ) {
			$pw .= PasswordGenerator::__getRandomASCIICharacter();
		}

		return $pw;
	}
}
