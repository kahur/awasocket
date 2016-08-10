<?php

require_once '../vendor/autoload.php';

$loop = new \AwaSocket\Loop();

$eventManager = new \AwaSocket\Events\Manager();
$eventManager->attach('join', function($event, $source, $socket) {
    print("New connection {$socket} accepted\n");
});

$socket = new \AwaSocket\Server\Adapter\Socket($loop);
$socket->setEventsManager($eventManager);
$resource = $socket->create();
$socket->bind($resource, '127.0.0.1', 5000);
$socket->listen($resource);

$server = new \AwaSocket\Server($socket);

//start full process of server
$server->start();
