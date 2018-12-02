<?php

namespace MissionControl;

include __DIR__.'/../../generated-conf/config.php';
use Device;
use DeviceQuery;
use Propel\Runtime\Exception\PropelException;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use UserQuery;

include __DIR__ . '/value_objects/Message.php';


class MissionControl implements MessageComponentInterface {

	protected $clients;

	private function __handleHelloMessage( HelloMessage $helloMsg ) {
		try {
			$device = DeviceQuery::create()->filterByHwid($helloMsg->getHwId())->findOneOrCreate()->save();
		} catch (PropelException $e) {
		}
	}

	private function __handleMessage( ConnectionInterface $sender, string $rawMessage ) {

		$parsed = json_decode( $rawMessage, true );

		switch ( $parsed['msgType'] ) {
			case 1:
				{
					$helloMsg = new HelloMessage();
					$helloMsg->deserialize( $rawMessage );
					$this->__handleHelloMessage( $helloMsg );
					break;
				}
		}
	}

	public function __construct() {
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen( ConnectionInterface $conn ) {
		$this->clients->attach( $conn );
	}

	public function onMessage( ConnectionInterface $from, $msg ) {

		$this->__handleMessage( $from, $msg );

		foreach ( $this->clients as $client ) {
			$client->send( "HELLO" );
		}
	}

	public function onClose( ConnectionInterface $conn ) {
		$this->clients->detach( $conn );
	}

	public function onError( ConnectionInterface $conn, \Exception $e ) {

	}
}
