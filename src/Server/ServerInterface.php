<?php

namespace AwaSocket\Server;

use AwaSocket\Events\ManagerInterface;

/**
 * Description of ServerInterface
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface ServerInterface
{

    public function start();

    public function stop();

    public function restart();

    public function setEventManager(ManagerInterface $manager);

    public function getEventManger();
}
