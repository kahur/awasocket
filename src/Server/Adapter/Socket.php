<?php

namespace AwaSocket\Server\Adapter;

use AwaSocket\Socket\Server\SocketInterface;
use AwaSocket\Events\ManagerInterface;
use AwaSocket\Loop\LoopInterface;
use AwaSocket\Server\AdapterInterface;

/**
 * Description of Socket
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Socket implements SocketInterface, AdapterInterface
{

    /**
     * @var ManagerInterface
     */
    protected $eventManager;

    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * Socket list
     */
    protected $sockets = [];

    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    public function bind($socket, $host, $port)
    {
        if (!socket_bind($socket, $host, $port)) {
            throw new Exception('Cannot bind on ' . $host . ':' . $port);
        }

        $this->listen($socket);

        return true;
    }

    public function listen($socket, $connectionLimit = 100)
    {
        if (!socket_listen($socket, $connectionLimit)) {
            throw new Exception('Failed to start listen on socket ' . $socket);
        }
    }

    public function close($socket)
    {
        return socket_close($socket);
    }

    public function connect($socket, $host, $port)
    {
        return socket_connect($socket, $host, $port);
    }

    public function create(array $options = null)
    {
        $socketType = SOL_TCP;
        if (isset($options['type']) && $options['type'] === SOL_UDP) {
            $socketType = SOL_UDP;
        }

        $socket = socket_create(AF_INET, SOCK_STREAM, $socketType);

        if (isset($options['blocking'])) {
            if (!$options['blocking']) {
                socket_set_nonblock($socket);
            }
        }

        $this->addSocket($socket);

        return $socket;
    }

    public function select(array $sockets)
    {
        $selected = $sockets;
        @socket_select($selected, $write = null, $except = null, 1);

        return $selected;
    }

    public function addSocket($socket)
    {
        if (!in_array($socket, $this->sockets)) {
            array_push($this->sockets, $socket);
        }

        return $this;
    }

    public function removeSocket($socket)
    {
        $key = array_search($socket, $this->sockets);
        if ($key !== false) {
            unset($this->sockets[$key]);
        }

        return $this;
    }

    public function accept($socket)
    {
        if (is_resource($socket)) {
            return socket_accept($socket);
        }
    }

    public function setEventsManager(ManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function getEventsManager()
    {
        return $this->eventManager;
    }

    public function getSockets()
    {
        return $this->sockets;
    }

    public function run()
    {
        $socketClass = $this;

        $this->loop->addEvent('socket', function($loop) use($socketClass) {
            //get selected sockets
            $sockets = $socketClass->getSockets();
            if ($sockets) {
                $sockets = $socketClass->select($sockets);

                foreach ($sockets as $socket) {
                    $accepted = $socketClass->accept($socket);
                    if ($accepted) {
                        $eventManager = $socketClass->getEventsManager();
                        if ($eventManager) {
                            $eventManager->fire('join', $socketClass, $accepted);
                        }

                        $socketClass->addSocket($accepted);
                    }
                }
            }
        });


        $this->loop->run();
    }

    public function stop()
    {
        $this->loop->stop();

        $sockets = $this->getSockets();

        foreach ($sockets as $socket) {
            $this->emit('disconnect', $socket);
            $this->close($socket);
        }

        $this->emit('stop');
    }

}
