<?php

include_once __DIR__.'/../../generated/SleepCycleQuery.php';

use Propel\Runtime\Propel;


class DrSlumber {

	private static function __calculateAvgSleepHours(): float {
		$con = Propel::getConnection();
		$stmt = $con->prepare(
			'SELECT AVG(sleep_cycle.duration) as avg_duration FROM sleep_cycle'
		);

		$stmt->execute();
		return $stmt->fetchAll()[0]['avg_duration'] / 60;
	}

	public static function getGlobalStatistics() {

		return array(
			'avg_sleep_hours' => DrSlumber::__calculateAvgSleepHours()
		);
	}

}