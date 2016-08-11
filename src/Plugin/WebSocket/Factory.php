<?php

namespace AwaSocket\Plugin\WebSocket;

use AwaSocket\Plugin\WebSocket;
use AwaSocket\Events\ManagerInterface;

/**
 * Description of Factory
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Factory
{

    public static function create(WebSocket $websocket, ManagerInterface $eventManager)
    {
        $events = array(
            'join',
            'disconnect',
            'message'
        );

        foreach ($events as $event) {
            $eventManager->attach($event, $websocket);
        }
    }

}
