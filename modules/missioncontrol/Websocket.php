<?php

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use MissionControl\MissionControl;
use Ratchet\WebSocket\WsServer;

require dirname( __DIR__ ) . '/../vendor/autoload.php';

$server = IoServer::factory(
	new HttpServer(
		new WsServer(
			new MissionControl() ) ),
	8777,
	'192.168.1.41'
);

$server->run();