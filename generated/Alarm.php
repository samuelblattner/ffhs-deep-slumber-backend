<?php

use Base\Alarm as BaseAlarm;

/**
 * Skeleton subclass for representing a row from the 'alarm' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Alarm extends BaseAlarm
{
	public function getLatestSimple() {
		return $this->getLatest('H:i');
	}

	public function getEarliestSimple() {
		return $this->getEarliest('H:i');
	}
}
