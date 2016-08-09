<?php

namespace AwaSocket\Socket\Server;

/**
 * Description of SocketInterface
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface SocketInterface extends \AwaSocket\SocketInterface
{

    /**
     * Bind certain socket to specific host and port
     * @param resource $socket
     * @param string $host
     * @param int $port
     */
    public function bind($socket, $host, $port);

    /**
     * Start listen on socket
     * @param resource $socket
     * @param int $connectionLimit connections
     * @return boolean
     */
    public function listen($socket, $connectionLimit = 100);

    /**
     * Accept connection on socket
     * @param resource socket
     * @return resource|false accepted socket
     */
    public function accept($socket);
}
