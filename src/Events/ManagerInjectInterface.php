<?php

namespace AwaSocket\Events;

use AwaSocket\Events\ManagerInterface;

/**
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface ManagerInjectInterface
{

    public function setEventsManager(ManagerInterface $eventsManager);

    public function getEventsManager();
}
