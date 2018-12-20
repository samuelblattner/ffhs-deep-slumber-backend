<?php

include_once __DIR__.'/../../generated/SleepCycleQuery.php';
include_once __DIR__.'/serializers/SleepCycleSerializer.php';
include_once __DIR__.'/serializers/SleepEventSerializer.php';

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;


class DrSlumber {

	private static function __calculateAvgSleepHours(User $user=null): float {
		$con = Propel::getConnection();
		$query = 'SELECT AVG(sleep_cycle.duration) as avg_duration FROM sleep_cycle';
		$devices = $user ? $user->getDevices() : null;
		if ($user !== null && sizeof($devices) > 0) {
			$query .= ' WHERE sleep_cycle.device_hwid = \'' . $devices[0]->getHwid() . '\'';
		}
		$stmt = $con->prepare($query);

		$stmt->execute();
		return $stmt->fetchAll()[0]['avg_duration'] / 60;
	}

	private static function __calculateLastUserSleepHours(User $user=null): float {
		$devices = $user->getDevices();
		if ($user !== null && sizeof($devices) > 0) {
			$cycle = SleepCycleQuery::create()->filterByDeviceHwid($devices[0]->getHwid())->orderById(Criteria::DESC)->findOne();
			return $cycle->getDuration() / 60;
		}
		return 0;
	}

	private static function __getLastSleepCycle(User $user=null): SleepCycle {
		$devices = $user->getDevices();
		if ($user !== null && sizeof($devices) > 0) {
			$cycle = SleepCycleQuery::create()->filterByDeviceHwid($devices[0]->getHwid())->orderById(Criteria::DESC)->findOne();
		}
		return $cycle;
	}


	public static function getGlobalStatistics() {

		return array(
			'avg_sleep_hours' => DrSlumber::__calculateAvgSleepHours()
		);
	}

	public static function getUserStatistics($user) {

		return array(
			'avg_sleep_hours' => DrSlumber::__calculateAvgSleepHours($user),
			'last_sleep_hours' => DrSlumber::__calculateLastUserSleepHours($user)
		);
	}

	public static function getUserLastSleepProfile($user) {

		$sleepCycle = DrSlumber::__getLastSleepCycle($user);
		$serializedSleepCycle = new SleepCycleSerializer(null, $sleepCycle);
		$events = SleepEventQuery::create()->filterBySleepCycle($sleepCycle)->orderByTimestamp(Criteria::ASC);
		$serializedEvents = new SleepEventSerializer(null, $events, True);

		return array(
			'events' => $serializedEvents->serialize(),
			'sleep_cycle' => $serializedSleepCycle->serialize()
		);
	}
}