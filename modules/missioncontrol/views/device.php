<?php

include __DIR__ . '/../commands/commands.php';
include __DIR__ . '/../serializers/device.php';


class DeviceListAPIView extends ListAPIView {

	protected function getCommandKey(): string {
		global $CMD_LIST_DEVICES;
		return $CMD_LIST_DEVICES;
	}

	protected function getSerializer() {
		return DeviceSerializer::class;
	}
}