<?php

namespace AwaSocket;

use AwaSocket\Socket\Server\SocketInterface;

/**
 * Description of Socket
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Socket implements SocketInterface
{

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

        return $socket;
    }

    public function accept($socket)
    {
        return socket_accept($socket);
    }

}
