<?php

require_once '../vendor/autoload.php';

$loop = new AwaSocket\Loop();
$socket = new \AwaSocket\Server\Adapter\Socket($loop);

$client = $socket->create();

$socket->connect($client, '127.0.0.1', 5000);
$socket->write($client, 'ahoj');
