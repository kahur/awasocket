<?php

namespace AwaSocket;

/**
 * Description of SocketInterface
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface SocketInterface
{

    /**
     * Create socket
     * @param array socket options
     * @return resource
     */
    public function create(array $options = null);

    /**
     * Connect to socket on specific host nad port
     * @params resource $socket
     * @params string $host;
     * @params int $port
     */
    public function connect($socket, $host, $port);

    /**
     * Close connection and socket
     * @param resource $socket Description
     */
    public function close($socket);
}
