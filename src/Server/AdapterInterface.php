<?php

namespace AwaSocket\Server;

use AwaSocket\Events\ManagerInterface;

/**
 * Description of ProtocolInterface
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface AdapterInterface
{

    public function run();

    public function stop();

    public function setEventsManager(ManagerInterface $eventManager);

    public function getEventsManager();
}
