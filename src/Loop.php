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

    /**
     * @var boolean
     */
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

    /**
     * Add handler to execute in loop
     * @param string $name
     * @param \Closure $handler
     *
     * @throws Exception
     */
    public function addEvent($name, $handler, &$data = null)
    {
        if (!$handler instanceof \Closure) {
            throw new Exception('Event must be closure or anonymouse function.');
        }

        $this->events[$name] = array('call' => $handler, 'data' => &$data);
    }

    /**
     * Get list of handlers
     * @param string $name
     *
     * @return \Closure[]
     */
    public function getEvents($name = null)
    {
        if ($name) {
            return (isset($this->events[$name])) ? $this->events[$name] : null;
        }

        return $this->events;
    }

    /**
     * Run loop
     */
    public function run()
    {
        $events = $this->getEvents();

        $sleep = $this->getSleepTime();
        $timeout = $this->getTimeout();

        foreach ($events as $name => $event) {
            call_user_func_array($event['call'], [$this, $event['data']]);
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

    /**
     * Set sleep time for loop in seconds
     * @param int $seconds
     * @throws Exception
     */
    public function setSleepTime($seconds = 0)
    {
        if (!is_numeric($seconds) || $seconds < 0) {
            throw new Exception('Sleep time can be just positive int');
        }

        $this->sleep = $seconds;
    }

    /**
     * Set timeout in seconds after which loop will be terminated
     * @param int $limit
     * @throws Exception
     *
     */
    public function setTimeout($limit = 0)
    {
        if (!is_numeric($limit) || $limit < 0) {
            throw new Exception('Time out can be just positive int');
        }

        $this->timeout = $limit;
    }

    /**
     * Set stop parameter to terminate loop
     */
    public function stop()
    {
        $this->stop = 1;
    }

    /**
     * Get sleep time in seconds
     * @return int Description
     */
    public function getSleepTime()
    {
        return $this->sleep;
    }

    /**
     * Get timeout in seconds
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Remove handler from loop
     * @param string $eventName
     */
    public function removeEvent($eventName)
    {
        if (isset($this->events[$eventName])) {
            unset($this->events[$eventName]);
        }
    }

    /**
     * Get runtime object to access runtime information
     * @return Runtime Description
     */
    public function getRuntime()
    {
        return $this->runtime;
    }

    public function create()
    {
        return new self();
    }

}
