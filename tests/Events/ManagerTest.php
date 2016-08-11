<?php

namespace Tests\Events;

use PHPUnit\Framework\TestCase;

//use Tests\Events\ManagerTest;

/**
 * Description of ManagerTest
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class ManagerTest extends TestCase
{

    public function testAttach()
    {
        $manager = new \AwaSocket\Events\Manager();

        $handler = function($arguments) {
            return $arguments;
        };

        $manager->attach('testEvent', $handler);

        $listeners = $manager->getListeners('testEvent');

        foreach ($listeners as $listener) {
            $this->assertEquals($handler, $listener);
        }
    }

    public function testDeattach()
    {
        $manager = new \AwaSocket\Events\Manager();

        $handler = function($manager) {
            if ($manager instanceof \AwaSocket\Events\Manager)
                return 'Got it!';
        };

        $handler2 = function($manager) {
            if ($manager instanceof \AwaSocket\Events\Manager)
                return 'Got it 1!';
        };

        $manager->attach('testEvent', $handler);
        $manager->attach('testEvent', $handler2);


        $manager->detach('testEvent', $handler);

        $listeners = $manager->getListeners('testEvent');

        foreach ($listeners as $listener) {
            if ($listener === $handler) {
                $this->fail('Listener not detached');
            }
        }

        $this->assertEquals($handler2, $listener);
    }

    public function testdetachAll()
    {
        $manager = new \AwaSocket\Events\Manager();

        $handler = function($manager) {
            if ($manager instanceof \AwaSocket\Events\Manager)
                return 'Got it!';
        };

        $handler1 = function($manager) {
            if ($manager instanceof \AwaSocket\Events\Manager)
                return 'Got it!1';
        };

        $manager->attach('testEvent', $handler);
        $manager->attach('testEvent', $handler1);

        $manager->attach('testEvent1', $handler);

        $listeners = $manager->getListeners('testEvent');
        $this->assertNotEmpty($listeners);

        $manager->detachAll('testEvent');

        $listeners = $manager->getListeners('testEvent');

        $this->assertEmpty($listeners);

        $listeners = $manager->getListeners('testEvent1');

        $this->assertNotEmpty($listeners);

        $manager->detachAll();

        $listeners = $manager->getListeners('testEvent1');

        $this->assertEmpty($listeners);
    }

    public function testFire()
    {
        $manager = new \AwaSocket\Events\Manager();

        $handler = function($event, $source, $data) {
            return 'result';
        };

        $manager->attach('testEvent', $handler);


        $fired = $manager->fire('testEvent', $this, 'test');
        if (empty($fired)) {
            $this->fail('No fired events');
        }

        foreach ($fired as $event) {
            $this->assertEquals('test', $event->getData());
            $this->assertEquals($this, $event->getSource());
            $this->assertEquals('result', $event->getResult());
        }
    }

    public function testfireMultipleEvents()
    {
        $manager = new \AwaSocket\Events\Manager();

        $handler = function($event, $source, $data) {
            return 'result';
        };

        $manager->attach('testEvent', $handler);

        $handler = function($event, $source, $data) {
            return 'result1';
        };

        $manager->attach('testEvent', $handler);

        $fired = $manager->fire('testEvent', $this, 'test');
        if (empty($fired)) {
            $this->fail('No fired events');
        }

        foreach ($fired as $key => $event) {

            if ($key === 0) {
                $this->assertEquals('test', $event->getData());
                $this->assertEquals($this, $event->getSource());
                $this->assertEquals('result', $event->getResult());
            } else {
                $this->assertEquals('test', $event->getData());
                $this->assertEquals($this, $event->getSource());
                $this->assertEquals('result1', $event->getResult());
            }
        }
    }

}
