<?php

namespace Tests\Server\Adapter;

use PHPUnit\Framework\TestCase;
use AwaSocket\Server\Adapter\Socket;

/**
 * Description of SocketTest
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class SocketTest extends TestCase
{

    public function testCreate()
    {
        $sock = new Socket();
        $master = $sock->create();

        $this->assertTrue(is_resource($master));

        $type = get_resource_type($master);

        $this->assertEquals('Socket', $type);

        socket_close($master);
    }

    public function testBind()
    {
        $sock = new Socket();
        $master = $sock->create();

        //bind socket
        $bind = $sock->bind($master, '127.0.0.1', 0);

        $this->assertTrue($bind);

        socket_close($master);
    }

    public function testConnect()
    {
        //create server to listen
        $sock = new Socket();

        $master = $sock->create(array('blocking' => false));
        $sock->bind($master, '127.0.0.1', 5000);
        $sock->listen($master);

        $socket = $sock->accept($master);

        $s = new Socket();
        $socket2 = $s->create();
        $connection = $s->connect($socket2, '127.0.0.1', 5000);

        $this->assertTrue($connection);

        socket_close($socket2);
        socket_close($master);
    }

    public function testClose()
    {
        $sock = new Socket();
        $master = $sock->create();

        $this->assertTrue(is_resource($master));

        socket_close($master);

        $this->assertFalse(is_resource($master));
    }

}
