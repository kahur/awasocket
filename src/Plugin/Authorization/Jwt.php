<?php

namespace AwaSocket\Plugin\Authorization;

use AwaSocket\Events\EventInterface;
use AwaSocket\Plugin\WebSocket\Client;

/**
 * Description of Jwt
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Jwt implements \AwaSocket\PluginInterface
{

    const SECRET = '>88:`I{?W/O0`?5';

    public function beforeHandshake(EventInterface $event, $source, array $data)
    {

        $headers = $data[0];
        $client = $data[1];
        $socket = $data[2];

        if (!preg_match('/Authorization: token (.*)\r\n/', $headers, $match) && !preg_match('/GET \/\?token=(.*) HTTP\/1.1/', $headers, $match)) {
            return false;
        }

        $token = $match[1];

        $key = base64_decode(self::SECRET);
        $jwt = \Firebase\JWT\JWT::decode($token, $key, array("HS512"));

        $user = $jwt->data->userId;

        $client->setUser($user);
    }

}
