<?php

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use MissionControl\MissionControl;
use Ratchet\WebSocket\WsServer;
use React\Socket\Server as Reactor;

require dirname( __DIR__ ) . '/../vendor/autoload.php';

$server = new IoServer(
	new HttpServer(new WsServer(new MissionControl())),
	new Reactor('127.0.0.1:8776', MissionControl::getMissionControlEventLoop()),
	MissionControl::getMissionControlEventLoop()
);

$server->run();
