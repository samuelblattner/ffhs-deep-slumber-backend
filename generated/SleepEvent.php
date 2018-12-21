<?php

use Base\SleepEvent as BaseSleepEvent;

/**
 * Skeleton subclass for representing a row from the 'sleep_event' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SleepEvent extends BaseSleepEvent
{
	public function getTimestampISO8601()
	{
		return $this->getTimestamp('Y-m-d\TH:i:sP');
	}

}
