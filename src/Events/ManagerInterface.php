<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AwaSocket\Events;

/**
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface ManagerInterface
{

    /**
     * Attach listener to events manager
     * @param string $eventType
     * @param object|callable $handler
     */
    public function attach($eventType, $handler);

    /**
     * Detach the listener from events manager
     * @param sring $eventType
     * @param object|callable $handler
     */
    public function detach($eventType, $handler);

    /**
     * Detach all events from the manager
     * @param string|null $eventType
     */
    public function detachAll($eventType = null);

    /**
     * Fire all listeners attached on certain type
     * @param string $type;
     * @param object $source
     * @param mixed $data
     *
     * @return mixed
     */
    public function fire($type, $source, $data = null);

    /**
     * Returns all attached listeners of certain type
     * @param string type
     * @return array
     */
    public function getListeners($type);
}
