<?php

namespace AwaSocket;

use AwaSocket\Loop\Exception;

/**
 * Description of Loop
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Loop implements Loop\LoopInterface
{

    protected $stop = false;
    protected $start;
    protected $runtime;
    protected $sleep = 0;
    protected $timeout = 0;
    protected $events = [];

    public function __construct()
    {
        $this->runtime = new Server\Runtime();
    }

    public function addEvent($name, $handler)
    {
        if (!$handler instanceof \Closure) {
            throw new Exception('Event must be closure or anonymouse function.');
        }

        $this->events[$name] = $handler;
    }

    public function getEvents($name = null)
    {
        if ($name) {
            return (isset($this->events[$name])) ? $this->events[$name] : null;
        }

        return $this->events;
    }

    public function run()
    {
        $events = $this->getEvents();

        $sleep = $this->getSleepTime();
        $timeout = $this->getTimeout();

        foreach ($events as $name => $event) {
            call_user_func_array($event, [$this]);
        }

        if ($sleep) {
            sleep($sleep);
        } else {
            usleep(5000);
        }

        $runtime = $this->runtime->getRuntime();
        //stop process
        if (($timeout > 0 && $timeout <= $runtime) || $this->stop) {
            $this->stop = 0;
            return false;
        }

        //repeat same process
        $this->run();
    }

    public function setSleepTime($seconds = 0)
    {
        if (!is_numeric($seconds) || $seconds < 0) {
            throw new Exception('Sleep time can be just positive int');
        }

        $this->sleep = $seconds;
    }

    public function setTimeout($limit = 0)
    {
        if (!is_numeric($limit) || $limit < 0) {
            throw new Exception('Time out can be just positive int');
        }

        $this->timeout = $limit;
    }

    public function stop()
    {
        $this->stop = 1;
    }

    public function getSleepTime()
    {
        return $this->sleep;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function removeEvent($eventName)
    {
        if (isset($this->events[$eventName])) {
            unset($this->events[$eventName]);
        }
    }

    public function getRuntime()
    {
        return $this->runtime;
    }

}
