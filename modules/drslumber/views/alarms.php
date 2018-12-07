<?php

include_once __DIR__.'/../commands/commands.php';
include_once __DIR__.'/../serializers/AlarmSerializer.php';


class AlarmListView extends ListAPIView {

	protected function getCommandKey(): string {
		global $CMD_LIST_ALARM;
		return $CMD_LIST_ALARM;
	}

	protected function getSerializer() {
		return AlarmSerializer::class;
	}
}


class AlarmUpdateView extends UpdateAPIView {

	protected function getCommandKey(): string {
		global $CMD_SAVE_ALARM;
		return $CMD_SAVE_ALARM;
	}

	protected function getSerializer() {
		return AlarmSerializer::class;
	}
}