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

    /**
     * Run
     */
    public function run();

    /**
     * Stop
     */
    public function stop();

    /**
     * Set events manager
     */
    public function setEventsManager(ManagerInterface $eventManager);

    /**
     * Get events manager
     */
    public function getEventsManager();
}
