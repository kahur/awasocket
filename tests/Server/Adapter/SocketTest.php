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

    protected $loop;

    public function setUp()
    {
        $loop = $this->getMockBuilder('\AwaSocket\Loop\LoopInterface')->getMock();

        $this->loop = $loop;
    }

    public function testCreate()
    {
        $sock = new Socket($this->loop);
        $master = $sock->create();

        $this->assertTrue(is_resource($master));

        $type = get_resource_type($master);

        $this->assertEquals('Socket', $type);

        socket_close($master);
    }

    public function testBind()
    {
        $sock = new Socket($this->loop);
        $master = $sock->create();

        //bind socket
        $bind = $sock->bind($master, '127.0.0.1', 0);

        $this->assertTrue($bind);

        socket_close($master);
    }

    public function testConnect()
    {
        //create server to listen
        $sock = new Socket($this->loop);

        $master = $sock->create(array('blocking' => false));
        $sock->bind($master, '127.0.0.1', 5000);
        $sock->listen($master);

        $socket = $sock->accept($master);

        $s = new Socket($this->loop);
        $socket2 = $s->create();
        $connection = $s->connect($socket2, '127.0.0.1', 5000);

        $this->assertTrue($connection);

        socket_close($socket2);
        socket_close($master);
    }

    public function testClose()
    {
        $sock = new Socket($this->loop);
        $master = $sock->create();

        $this->assertTrue(is_resource($master));

        socket_close($master);

        $this->assertFalse(is_resource($master));
    }

    public function testWrite()
    {
        $sock = new Socket($this->loop);

        $master = $sock->create(array('blocking' => false));
        $sock->bind($master, '127.0.0.1', 5000);
        $sock->listen($master);

        $socket = $sock->accept($master);

        $s = new Socket($this->loop);
        $socket2 = $s->create();
        $s->connect($socket2, '127.0.0.1', 5000);
//
        $t = $s->write($socket2, 'Test');
//
        $this->assertEquals(strlen('Test'), $t);

        $sock->close($master);
        $sock->close($socket2);
    }

    public function testRead()
    {
        $sock = new Socket($this->loop);

        $master = $sock->create(array('blocking' => false));
        $sock->bind($master, '127.0.0.1', 5000);
        $sock->listen($master);

        $socket = $sock->accept($master);

        $s = new Socket($this->loop);
        $socket2 = $s->create();
        $s->connect($socket2, '127.0.0.1', 5000);

        socket_set_nonblock($socket2);
        $t = $s->write($socket2, 'Test');

        $socks = array($master);

        $selected = $sock->select($socks);

        $accept = $sock->accept($selected[0]);

        $message = $sock->read($accept);
        $this->assertEquals('Test', $message);

        $sock->close($master);
        $sock->close($socket2);
    }

}
