<?php

namespace Tests\Events;

use PHPUnit\Framework\TestCase;
use AwaSocket\Events\Event;

/**
 * Description of EventTest
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class EventTest extends TestCase
{

    protected $event;

    public function setUp()
    {
        $this->event = new Event('test', $this, 'data');
        parent::setUp();
    }

    public function testGetData()
    {

        $this->assertEquals('data', $this->event->getData());
    }

    public function testSetData()
    {
        $this->event->setData('newdata');

        $this->assertEquals('newdata', $this->event->getData());
    }

    public function testGetSource()
    {
        $this->assertEquals($this, $this->event->getSource());
    }

    public function testSetSource()
    {
        $class = new \stdClass();
        $class1 = new \stdClass();
        $class->test = 'test';
        $this->event->setSource($class);

        $this->assertEquals($class, $this->event->getSource());

        $this->assertNotEquals($class1, $this->event->getSource());
    }

    public function testSetType()
    {
        $this->event->setType('newType');

        $this->assertEquals('newType', $this->event->getType());
    }

    public function testWrongType()
    {
        $this->expectException(\AwaSocket\Events\Exception::class);

        $this->event->setType(new \stdClass());
    }

    public function testWrongSource()
    {
        $this->expectException(\AwaSocket\Events\Exception::class);

        $this->event->setSource('');
    }

}
