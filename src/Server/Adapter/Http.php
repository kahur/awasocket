<?php

namespace AwaSocket\Server\Adapter;

/**
 * Description of Http
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface Http
{

    public function setHost($host);

    public function setPort($port);

    public function setProtocol($protocol);

    public function getHost();

    public function getPort();
}
