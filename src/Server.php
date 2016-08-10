<?php

namespace AwaSocket;

/**
 * Description of Server
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Server implements Server\ServerInterface
{

    /**
     * Socket library
     * @var Server\AdapterInterface
     */
    protected $adapter;
    protected $host;
    protected $port;
    protected $master;

    /**
     * @var Events\ManagerInterface
     */
    protected $eventManger;

    public function __construct(Server\AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
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

        $this->adapter->run();

        if ($this->eventManger) {
            $this->eventManger->fire('afterStart', $this);
        }
    }

    public function stop()
    {
        if ($this->eventManger) {
            $this->eventManger->fire('beforeStop', $this, $this);
        }

        $this->adapter->stop();

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
        return $this->adapter;
    }

}
