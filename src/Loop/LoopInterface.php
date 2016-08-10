<?php

namespace AwaSocket\Loop;

/**
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface LoopInterface
{

    public function setTimeout($limit = 0);

    public function getTimeout();

    public function addEvent($eventName, $handler);

    public function removeEvent($eventName);

    public function getEvents($name = null);

    public function setSleepTime($seconds = 0);

    public function getSleepTime();

    public function run();

    public function stop();
}
