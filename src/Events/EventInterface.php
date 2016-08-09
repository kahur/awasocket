<?php

namespace AwaSocket\Events;

/**
 * Description of EventInterface
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface EventInterface
{

    /**
     * Get data
     */
    public function getData();

    /**
     * Set event data
     * @param mixed $data
     * @return EventInterface
     */
    public function setData($data = null);

    /**
     * Get event type
     */
    public function getType();

    /**
     * Set event type
     * @param string $type;
     * @return EventInterface
     */
    public function setType($type);

    /**
     * Set source object
     * @param Object $source
     * @return EventInterface
     */
    public function setSource($source);

    /**
     * Get source object where event was called
     * @return Object
     */
    public function getSource();

    /**
     * Set result of event
     * @param mixed $result
     * @return EventInterface
     */
    public function setResult($result);

    /**
     * Get result of event
     */
    public function getResult();
}
