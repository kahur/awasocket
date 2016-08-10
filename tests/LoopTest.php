<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * Description of LoopTest
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class LoopTest extends TestCase
{

    public function testAddEvent()
    {
        $loop = new \AwaSocket\Loop();
        $loop->addEvent('test', function() {
            return 'test';
        });

        $event = $loop->getEvents('test');

        $this->assertNotEmpty($event);

        $value = $event();

        $this->assertEquals('test', $value);
    }

    public function testRemoveEvent()
    {
        $loop = new \AwaSocket\Loop();
        $loop->addEvent('test', function() {

        });

        $loop->removeEvent('test');

        $event = $loop->getEvents('test');

        $this->assertNull($event);
    }

    public function testWrongAddEvent()
    {
        $this->expectException(\AwaSocket\Loop\Exception::class);
        $this->expectExceptionMessage('Event must be closure or anonymouse function.');
        $loop = new \AwaSocket\Loop();
        $loop->addEvent('test', new \stdClass());
    }

    public function testWrongValueTimeout()
    {
        $this->expectException(\AwaSocket\Loop\Exception::class);
        $this->expectExceptionMessage('Time out can be just positive int');

        $loop = new \AwaSocket\Loop();
        $loop->setTimeout(-1);
    }

    public function testRunStop()
    {
        $loop = new \AwaSocket\Loop();
        $called = false;
        $loop->addEvent('exit', function($loop) use(&$called) {
            $loop->stop();
            $called = true;
        });

        $loop->run();

        $this->assertTrue($called);
    }

    public function testTimeout()
    {
        $loop = new \AwaSocket\Loop();
        $loop->setTimeout(2);
        $loop->run();

        $runtime = $loop->getRuntime();
        $runtime->stop();
        $time = $runtime->getRuntime();

        $this->assertTrue(($time >= 2));
    }

    public function testSleep()
    {
        $mock = $this->getMockBuilder('Dummy');
        $mock->setMethods(array('fire'));
        $mock = $mock->getMock();
        $mock->expects($this->once())
                ->method('fire');


        $loop = new \AwaSocket\Loop();
        $loop->addEvent('test', function() use($mock) {
            $mock->fire();
        });

        $loop->setTimeout(2);
        $loop->setSleepTime(2);
        $start = time();
        $loop->run();
        $end = time();

        $runtime = $end - $start;

        $this->assertEquals(2, $runtime);
    }

}
