<?php

namespace AwaSocket\Events;

/**
 * Description of EventManager
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Manager implements ManagerInterface
{

    protected $_listeners = array();
    protected $_firedEvents = [];

    /**
     * Attach handler to eventType
     * @param string $eventType
     * @param \Closure|Object $handler closure to call, object with event methods if method in object does not exist event will not be fired
     */
    public function attach($eventType, $handler)
    {
        if (!isset($this->_listeners[$eventType])) {
            $this->_listeners[$eventType] = array();
        }

        if (!in_array($handler, $this->_listeners[$eventType])) {
            array_push($this->_listeners[$eventType], $handler);
        }
    }

    /**
     * Remove handler from event
     * @param string $eventType
     * @param \Closure|Object $handler
     * @return boolean
     */
    public function detach($eventType, $handler)
    {
        $listeners = $this->getListeners($eventType);

        $key = array_search($handler, $listeners);
        if ($key !== false) {

            unset($this->_listeners[$eventType][$key]);
            return true;
        }

        return false;
    }

    /**
     * Remove all events or all events by type
     * @param string|null $eventType
     * @return boolean
     */
    public function detachAll($eventType = null)
    {
        if (!$eventType) {
            $this->_listeners = array();
            return true;
        }

        $key = array_key_exists($eventType, $this->_listeners);
        if ($key) {
            unset($this->_listeners[$eventType]);
            return true;
        }

        return false;
    }

    /**
     * Fire handlers listening on eventType
     * @param string $type
     * @param Object $source source object where fire was called
     * @param mixed $data
     *
     * @return Event[] list of fired events
     */
    public function fire($type, $source, $data = null)
    {
        $listeners = $this->getListeners($type);
        $t = $type;
        if (strpos($type, ':') !== false) {
            $types = explode(":", $type);
            $t = end($types);
        }

        foreach ($listeners as $listener) {
            if (is_object($listener) && !$listener instanceof \Closure) {
                if (method_exists($listener, $t)) {
                    $event = new Event($type, $source, $data);

                    $result = $listener->{$t}($event, $source, $data);

                    $event->setResult($result);

                    $this->_firedEvents[] = $event;
                }
            } else if (is_callable($listener)) {

                $event = new Event($type, $source, $data);
                $args = [$event, $source, $data];

                $result = call_user_func_array($listener, $args);

                $event->setResult($result);

                $this->_firedEvents[] = $event;
            }
        }

        return $this->_firedEvents;
    }

    /**
     * Get handlers by event type
     * @param string $type
     * @return Object[]|\Closure[] list of handlers
     */
    public function getListeners($type)
    {
        return (isset($this->_listeners[$type])) ? $this->_listeners[$type] : [];
    }

}
