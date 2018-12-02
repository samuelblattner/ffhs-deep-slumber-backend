<?php

abstract class MessageType {
	const HELLO = 1;
	const SETTINGS = 2;
	const COMMAND = 3;
	const EVENT = 4;
	const HEARTBEAT = 100;
}

abstract class EventType {
	const START_REC = 1;
	const STOP_REC = 2;
	const PAUSE_REC = 3;
	const START_WAKING = 10;
	const USER_ABORT_WAKING = 11;
	const END_WAKING = 12;
	const MOVEMENT = 1000;
	const TEMPERATURE = 1001;
	const PRESSURE = 1002;
	const HUMIDITY = 1003;
}