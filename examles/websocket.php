<?php

require_once '../vendor/autoload.php';

$loop = new \AwaSocket\Loop();



$eventManager = new \AwaSocket\Events\Manager();

\AwaSocket\Plugin\WebSocket\Factory::create(new \AwaSocket\Plugin\WebSocket($loop), $eventManager);

$socket = new \AwaSocket\Server\Adapter\Socket($loop);
$socket->setEventsManager($eventManager);
$resource = $socket->create();
$socket->bind($resource, '127.0.0.1', 5001);
$socket->listen($resource);

$server = new \AwaSocket\Server($socket);

//start full process of server
$server->start();

