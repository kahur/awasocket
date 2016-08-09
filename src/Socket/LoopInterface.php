<?php

namespace AwaSocket\Socket;

use AwaSocket\Events\ManagerInterface;

/**
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface LoopInterface
{

    public function start();

    public function stop();

    public function setObserver(ManagerInterface $manager);
}
