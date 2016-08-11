<?php

namespace AwaSocket\Plugin;

use AwaSocket\Events\Event;

/**
 * Description of WebSocket
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class WebSocket implements \AwaSocket\PluginInterface
{

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
        $client->disconnect();
        $this->removeClient($client);
        unset($client);
    }

    public function message(Event $event)
    {
        $data = $event->getData();
        $socket = $data[0];
        $message = $data[1];

        $client = $this->getClientBySocket($socket);
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

}
