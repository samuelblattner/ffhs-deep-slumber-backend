<?php

namespace MissionControl;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class MissionControl implements MessageComponentInterface {

	protected $clients;

	public function __construct() {
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn) {
		$this->clients->attach($conn);

		echo 'CONNECTION!!!!!!!!!!!!!!';
	}

	public function onMessage(ConnectionInterface $from, $msg) {
		echo 'MESSAGE '.$msg;

		sleep(2);
		foreach ($this->clients as $client) {
			echo 'jup';
			$client->send("HELLO");

		}
	}

	public function onClose(ConnectionInterface $conn) {
		echo 'CLOSED!!!!!!!!!!!!!!!!!!!';
	}

	public function onError(ConnectionInterface $conn, \Exception $e) {

	}
}