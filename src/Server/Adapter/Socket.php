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

    /**
     * Master socket
     */
    protected $master;

    /**
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    /**
     * Bind socket to host and port
     * @param resource $socket
     * @param string $host
     * @param int $port
     * @throws Exception
     * @return boolean
     */
    public function bind($socket, $host, $port)
    {
        if (!socket_bind($socket, $host, $port)) {
            throw new Exception('Cannot bind on ' . $host . ':' . $port);
        }

        return true;
    }

    /**
     * Start listening for incomming connections
     * @param resource $socket
     * @param int $connectionLimit
     * @throws Exception
     */
    public function listen($socket, $connectionLimit = 100)
    {
        if (!socket_listen($socket, $connectionLimit)) {
            throw new Exception('Failed to start listen on socket ' . $socket);
        }
    }

    /**
     * Close certain socket
     * @param resource $socket
     *
     * @return boolean
     */
    public function close($socket)
    {
        return socket_close($socket);
    }

    /**
     * Connect to socket
     * @param resource $socket
     * @param string $host
     * @param int $port
     *
     * return boolean
     */
    public function connect($socket, $host, $port)
    {
        return socket_connect($socket, $host, $port);
    }

    /**
     * Create socket resource
     * @param array $options Options to create socket
     *  - blocking - boolean set blocking type of socket or false to non blocking
     *  - type - SOL_UDP for UDP socket
     *
     * @return resource
     */
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

        $this->master = $socket;
        $this->addSocket($socket);

        return $socket;
    }

    public function getMaster()
    {
        return $this->master;
    }

    /**
     * Select active sockets
     * @param resource[] $sockets
     *
     * @return resource[] selected socket with activity
     */
    public function select(array $sockets)
    {
        $selected = $sockets;
        @socket_select($selected, $write = null, $except = null, 1);

        return $selected;
    }

    /**
     * Append socket to the list
     * @param resource $socket
     *
     * @return Socket
     */
    public function addSocket($socket)
    {
        if (!in_array($socket, $this->sockets)) {
            array_push($this->sockets, $socket);
        }

        return $this;
    }

    public function read($socket)
    {
        $message = '';
        while ($bytes = socket_recv($socket, $msg, 2048, MSG_DONTWAIT)) {

            if ($msg == '') {
                break;
            } else if ($bytes === 0) {
                echo "Disconnecting\n";
                return false;
            }

            $message .= $msg;
            usleep(5);
        }

        return $message;
    }

    public function write($socket, $message)
    {
        $sent = 0;
        $length = strlen($message);
        while (true) {
            $sent += socket_write($socket, $message);

            if ($sent >= $length) {
                break;
            }
            usleep(5);
        }

        return $sent;
    }

    /**
     * Remove socket from list
     * @param resource $socket
     *
     * @return Socket
     */
    public function removeSocket($socket)
    {
        $key = array_search($socket, $this->sockets);
        if ($key !== false) {
            unset($this->sockets[$key]);
        }

        return $this;
    }

    /**
     * Accept incomming connection to socket and connections to socket
     *
     * @param resource $socket
     *
     * @return resource|false Return accepted resource of given socket
     */
    public function accept($socket)
    {
        if (is_resource($socket)) {
            return socket_accept($socket);
        }

        return false;
    }

    /**
     * Set events manager for socket, if manager is set event's are beign fired
     *
     * @param ManagerInterface $eventManager
     */
    public function setEventsManager(ManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * Get event manager
     * @return ManagerInterface
     */
    public function getEventsManager()
    {
        return $this->eventManager;
    }

    /**
     * Get active socket resource list
     *
     * @return resource[]
     */
    public function getSockets()
    {
        return $this->sockets;
    }

    /**
     * Run socket listeing on all active sockets and register socket loop
     * @event
     * - join($event, $socket, $acceptedResource)
     */
    public function run()
    {
        $socketClass = $this;
        $eventManager = $socketClass->getEventsManager();
        $this->loop->addEvent('socket', function($loop) use($socketClass, $eventManager) {
            //get selected sockets
            $sockets = $socketClass->getSockets();
            if ($sockets) {
                $sockets = $socketClass->select($sockets);

                foreach ($sockets as $socket) {
                    if ($socket === $socketClass->getMaster()) {
                        $accepted = $socketClass->accept($socket);
                        if ($accepted) {
                            if ($eventManager) {
                                $eventManager->fire('join', $socketClass, $accepted);
                            }
                            $socketClass->addSocket($accepted);
                        }
                    } else {
                        //perform other actions
                        $data = $socketClass->read($socket);
                        if (!$data) {
                            $eventManager->fire('disconnect', $socketClass, $socket);
                            $socketClass->close($socket);
                        } else if ($data !== '') {
                            $eventManager->fire('message', $socketClass, array($socket, $data));
                        } else {
                            $eventManager->fire('change', $socketClass, $socket);
                        }
                    }
                }
            }
        });


        $this->loop->run();
    }

    /**
     * Stop socket process, close all active sockets and stop loop
     * @events
     * - stop($event, $socket)
     */
    public function stop()
    {
        $this->loop->stop();

        $sockets = $this->getSockets();

        foreach ($sockets as $socket) {
            $this->close($socket);
        }
    }

}
