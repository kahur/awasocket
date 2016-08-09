<?php

namespace AwaSocket;

use AwaSocket\Socket\Server\SocketInterface;
use AwaSocket\Server\ProtocolInterface;

/**
 * Description of Server
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Server implements Server\ServerInterface
{

    /**
     * Socket library
     * @var SocketInterface
     */
    protected $socket;
    protected $host;
    protected $port;
    protected $master;

    /**
     * @var Events\ManagerInterface
     */
    protected $eventManger;

    public function __construct(SocketInterface $socket, Server\Adapter $adapter)
    {

        $this->socket = $socket;
    }

    public function restart()
    {
        $this->stop();
        sleep(1);
        $this->start();
    }

    public function start()
    {
        if ($this->eventManger) {
            $this->eventManger->fire('beforeStart', $this);
        }

        $socket = $this->socket->create();
        $this->master = $socket;

        $this->socket->bind($this->master, $this->host, $this->port);
        $this->socket->listen($this->master);

        if ($this->eventManger) {
            $this->eventManger->fire('afterStart', $this);
        }
    }

    public function stop()
    {
        if ($this->eventManger) {
            $this->eventManger->fire('beforeStop', $this, $this->master);
        }

        $this->socket->close($this->master);

        if ($this->eventManger) {
            $this->eventManger->fire('afterStop', $this);
        }
    }

    public function getEventManger()
    {
        return $this->eventManger;
    }

    public function setEventManager(Events\ManagerInterface $manager)
    {
        $this->eventManger = $manager;
    }

    /**
     * Get socket library
     * @return SocketInterface
     */
    public function getSocket()
    {
        return $this->socket;
    }

}
