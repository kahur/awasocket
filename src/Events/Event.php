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

    public function getData()
    {
        return $this->data;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setData($data = null)
    {
        $this->data = $data;

        return $this;
    }

    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    public function setSource($source)
    {
        if (!is_object($source)) {
            throw new Exception('Source must be object.');
        }

        $this->source = $source;

        return $this;
    }

    public function setType($type)
    {
        if (!is_string($type)) {
            throw new Exception('Type must be string');
        }

        $this->type = $type;

        return $this;
    }

}
