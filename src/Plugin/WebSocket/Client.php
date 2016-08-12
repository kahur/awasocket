<?php

namespace AwaSocket\Plugin\WebSocket;

/**
 * Description of Client
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Client
{

    protected $id;
    protected $user;
    protected $handshake = false;
    protected $socket;
    protected $pid;
    protected $isConnect;

    public function __construct($id, $socket)
    {
        $this->id = $id;
        $this->socket = $socket;
        $this->isConnect = true;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSocket()
    {
        return $this->socket;
    }

    public function hasHandshake()
    {
        return $this->handshake;
    }

    public function setHandshake($status)
    {
        $this->handshake = $status;
    }

    public function disconnect()
    {
        $this->isConnect = false;
        posix_kill($this->getPid(), SIGKILL);
    }

    public function isConnected()
    {
        return $this->isConnect;
    }

    public function getPid()
    {
        return $this->pid;
    }

    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    public function process(\AwaSocket\Loop\LoopInterface $loop)
    {
        $pid = pcntl_fork();
        $client = &$this;
        if ($pid) {
            $this->setPid($pid);
        } else {
            $loop = $loop->create();
            $loop->run();
        }
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function __destruct()
    {
        $this->isConnect = false;
    }

}
