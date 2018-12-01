<?php

use Ratchet\Server\IoServer;
use MissionControl\MissionControl;

require dirname(__DIR__).'/../vendor/autoload.php';

$server = IoServer::factory(
	new MissionControl(),
	8777,
	'192.168.1.41'
);

$server->run();