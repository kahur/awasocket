<?php

require_once '../vendor/autoload.php';

$loop = new \AwaSocket\Loop();



$eventManager = new \AwaSocket\Events\Manager();
\AwaSocket\Plugin\WebSocket\Factory::create(new \AwaSocket\Plugin\WebSocket($loop), $eventManager);
$eventManager->attach('beforeHandshake', new \AwaSocket\Plugin\Authorization\Jwt());



$socket = new \AwaSocket\Server\Adapter\Socket($loop);
$socket->setEventsManager($eventManager);
$resource = $socket->create();
$socket->bind($resource, 'api.pukkr.kamil', 5001);
$socket->listen($resource);

$server = new \AwaSocket\Server($socket);

//start full process of server
$server->start();

