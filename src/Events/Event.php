<?php

namespace AwaSocket\Events;

/**
 * Description of Event
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Event implements EventInterface
{

    protected $data;
    protected $source;
    protected $type;
    protected $result;

    /**
     * @param string $type
     * @param Object $source
     * @param mixed $data
     * @param mixed $result
     */
    public function __construct($type, $source, $data = null, $result = null)
    {
        $this->setType($type);
        $this->setSource($source);

        if ($data) {
            $this->setData($data);
        }

        if ($result) {
            $this->setResult($result);
        }
    }

    /**
     * Return data from event
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return result of event
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Get source object where event has been fired
     * @return Object
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get name of event what was fired
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set data to event
     * @param mixed $data
     * @return Event
     */
    public function setData($data = null)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set result of event
     * @param mixed $result
     * @return Event
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Set source object where event has been fired
     * @param Object $source
     * @throws Exception
     * @return Event
     */
    public function setSource($source)
    {
        if (!is_object($source)) {
            throw new Exception('Source must be object.');
        }

        $this->source = $source;

        return $this;
    }

    /**
     * Set type ( name ) of fired event
     * @param string $type
     * @throws Exception
     * @return string
     */
    public function setType($type)
    {
        if (!is_string($type)) {
            throw new Exception('Type must be string');
        }

        $this->type = $type;

        return $this;
    }

}
