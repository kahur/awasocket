<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../vendor/autoload.php';

$loop = new AwaSocket\Loop();
$socket = new \AwaSocket\Server\Adapter\Socket($loop);

$client = $socket->create();



$socket->connect($client, '127.0.0.1', 5000);
while (true) {

    $socket->write($client, 'ahoj');
    sleep(5);
}
