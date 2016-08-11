# Socket Server library
Multi-thread fully scalable socket library
# Installation
```sh
$ git clone https://github.com/kamilhurajt/awasocket.git
$ cd web-socket
$ composer update
```
# Usage
Create simple socket server
```php
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
```

Create WebSocket v13 server
```php
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
```

Create client
```php
require_once '../vendor/autoload.php';

$loop = new AwaSocket\Loop();
$socket = new \AwaSocket\Server\Adapter\Socket($loop);

$client = $socket->create();

$socket->connect($client, '127.0.0.1', 5000);
$socket->write($client, 'ahoj');
```

# Events

* ```join($event, $source, $data) ```
* ```disconnect($event, $source, $data)```
* ```message($event, $source, [$socket, $message])```
* ```beforeStart($event, $source)```
* ```afterStart($event, $source)```
