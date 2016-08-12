<?php

namespace AwaSocket\Plugin;

use AwaSocket\Events\Event;
use AwaSocket\Events\ManagerInjectInterface;
use AwaSocket\Events\ManagerInterface;

/**
 * Description of WebSocket
 * @todo comment
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class WebSocket implements \AwaSocket\PluginInterface, ManagerInjectInterface
{

    /**
     * @var ManagerInterface
     */
    protected $eventsManager;

    /**
     * @var WebSocket\Client[]
     */
    protected $clients = [];

    /**
     * @var resource[]
     */
    protected $sockets = [];
    protected $loop;

    public function __construct(\AwaSocket\Loop\LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    public function join(Event $event)
    {
        $clientSocket = $event->getData();

        $client = $this->getClientBySocket($clientSocket);

        if (!$client) {
            $client = new WebSocket\Client(uniqid(), $clientSocket);
            $client->process($this->loop);

            $this->addClient($client);
        }
    }

    public function disconnect(Event $event)
    {
        $socket = $event->getData();
        $socketObject = $event->getSource();

        $client = $this->getClientBySocket($socket);
        if ($client) {
            $client->disconnect();
        }

        $message = WebSocket\Helper\Message::encode(3000, 'close');
        $socketObject->write($socket, $message);


        $this->removeClient($client);
        unset($client);
    }

    public function message(Event $event)
    {
        $data = $event->getData();
        $socket = $data[0];
        $client = $this->getClientBySocket($socket);
        $socketClass = $event->getSource();

        $message = $data[1];

        if (!$client->hasHandshake()) {
            if ($this->eventsManager) {
                $this->eventsManager->fire('beforeHandshake', $socketClass, array($message, $client, $socket));
            }

            $this->handshake($client, $socketClass, $socket, $message);
        } else {
            $message = WebSocket\Helper\Message::decode($message);
            if ($this->eventsManager) {
                $this->eventsManager->fire('websocket:message', $this, array($message, $event));
            }
        }
    }

    public function addClient(WebSocket\Client $client)
    {
        $this->clients[] = $client;
        $this->sockets[] = $client->getSocket();
    }

    public function removeClient(WebSocket\Client $client)
    {
        $key = array_search($client, $this->clients);

        if ($key !== false) {
            unset($this->clients[$key]);
            unset($this->sockets[$key]);
        }
    }

    public function isClient($socket)
    {
        if (in_array($socket, $this->sockets)) {
            return true;
        }

        return false;
    }

    public function getClientBySocket($socket)
    {
        if (!$this->isClient($socket)) {
            return null;
        }

        $key = array_search($socket, $this->sockets);

        return $this->clients[$key];
    }

    public function handshake(WebSocket\Client $client, \AwaSocket\Socket\Server\SocketInterface $socketClass, $socket, $headers)
    {
        $handshake = new WebSocket\Helper\Handshake($headers);
        if ($handshake->version !== 13) {
            $socketClass->write($socket, 'Client does not support web socket v 13');
            $socketClass->close($socket);
            $client->disconnect();
        } else {
            $client->setHandshake(true);
            $socketClass->write($socket, $handshake->getUpgradeHeader());
        }
    }

    public function getEventsManager()
    {
        return $this->eventsManager;
    }

    public function setEventsManager(ManagerInterface $eventsManager)
    {
        $this->eventsManager = $eventsManager;
    }

}
