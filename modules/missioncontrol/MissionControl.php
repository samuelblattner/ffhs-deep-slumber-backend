<?php

namespace MissionControl;

include __DIR__ . '/../../generated-conf/config.php';

use Clue\React\Redis\Client;
use Clue\React\Redis\Factory;
use Propel\Runtime\ActiveQuery\Criteria;
use SleepCycle;
use Device;
use DeviceQuery;
use EventType;
use MessageType;
use Propel\Runtime\Exception\PropelException;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SleepCycleQuery;
use SleepEvent;
use UserQuery;
use React;
use Clue\Redis;

include_once __DIR__ . '/value_objects/Message.php';


class MissionControl implements MessageComponentInterface {

	protected $clients;
	protected $connectionDeviceMap;
	protected $deviceConnectionMap;

	private static $eventLoop = null;

	public static function getMissionControlEventLoop(): React\EventLoop\LoopInterface {
		if (MissionControl::$eventLoop == null) {
			MissionControl::$eventLoop = React\EventLoop\Factory::create();
		}
		return MissionControl::$eventLoop;
	}

	public static function pushMessage($deviceId, AbstractMessage $payload) {
		$factory = new Factory(MissionControl::getMissionControlEventLoop());

		$factory->createClient('redis://127.0.0.1:6379')->then(function (Client $client) use ($payload) {
			$client->publish('websocket_out', $payload->serialize());
			$client->end();

		}, function($error) {echo $error;});

		MissionControl::getMissionControlEventLoop()->run();

	}

	private function __handleHelloMessage( HelloMessage $helloMsg, ConnectionInterface $fromSender ) {
		try {
			$device                                                          = DeviceQuery::create()->filterByHwid( $helloMsg->getHwId() )->findOneOrCreate()->save();
			$this->connectionDeviceMap[$this->clients->getHash($fromSender)] = $helloMsg->getHwId();
			$this->deviceConnectionMap[$helloMsg->getHwId()] = $fromSender;
		} catch ( PropelException $e ) {

		}
	}

	private function __handleEvent( ConnectionInterface $sender, Event $event ) {

		$device = DeviceQuery::create()->findOneByHwid($this->connectionDeviceMap[$this->clients->getHash($sender)]);
		$openSleepCycle = SleepCycleQuery::create()->filterByDeviceHwid($device->getHwid())->filterByStop(null)->orderById(Criteria::DESC)->findOne();

		switch ( $event->event_type ) {
			case EventType::START_REC:
				{
					$sleepCycle = new SleepCycle();
					$sleepCycle->setDevice($device);
					$sleepCycle->setStart($event->timestamp);
					$sleepCycle->save();
					break;
				}

			case EventType::STOP_REC:
				{
					$endtime = strtotime($event->timestamp);
					if ($openSleepCycle != null) {
						$openSleepCycle->setStop($endtime);
						$openSleepCycle->setDuration(($endtime - $openSleepCycle->getStart()->getTimestamp())/60);
						$openSleepCycle->save();
					}
					break;
				}
			case EventType::MOVEMENT:
			case EventType::TEMPERATURE:
			case EventType::HUMIDITY:
			case EventType::PRESSURE:
				{
					if ($openSleepCycle != null) {
						$sleepEvent = new SleepEvent();
						$sleepEvent->setTimestamp($event->timestamp);
						$sleepEvent->setType($event->event_type);
						$sleepEvent->setSleepCycle($openSleepCycle);
						$sleepEvent->setValue($event->value);
						$sleepEvent->save();
					}
					break;
				}
		}
	}

	private function __handleMessage( ConnectionInterface $sender, string $rawMessage ) {

		$parsed = json_decode( $rawMessage, true );

		switch ( $parsed['msgType'] ) {
			case MessageType::HELLO:
				{
					$helloMsg = new HelloMessage();
					$helloMsg->deserialize( $rawMessage );
					$this->__handleHelloMessage( $helloMsg, $sender);
					break;
				}
			case MessageType::EVENT:
				{
					if (!array_key_exists($this->clients->getHash($sender), $this->connectionDeviceMap)) {
						return;
					}
					$event = new Event();
					$event->deserialize( $rawMessage );
					$this->__handleEvent( $sender, $event );
				}
		}
	}

	/**
	 * MissionControl constructor.
	 */
	public function __construct() {
		$this->clients             = new \SplObjectStorage;
		$this->connectionDeviceMap = array();
		$this->deviceConnectionMap = array();
		$this->setupRedisListener(MissionControl::getMissionControlEventLoop());
	}

	private function handleOutMessage($payload) {
		if (array_key_exists('deviceId', $payload) && array_key_exists($payload['deviceId'], $this->deviceConnectionMap)) {
			$connection = $this->deviceConnectionMap[$payload['deviceId']];
			$connection->send(json_encode($payload));
		}
	}

	public function setupRedisListener($loop) {
		$factory = new Factory($loop);

		$factory->createClient('redis://127.0.0.1:6379')->then(function (Client $client) {
			$client->on('message', function($channel, $serialized_payload) {
				if ($channel == 'websocket_out') {
					$this->handleOutMessage(json_decode($serialized_payload, TRUE));
				}
			});
			$client->subscribe('websocket_out');

		}, function($error) {echo 'nop'; echo $error;});

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
