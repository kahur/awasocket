<?php

namespace AwaSocket\Plugin\WebSocket\Helper;

/**
 * Description of Handshake
 *
 * @author kamil
 */
class Handshake
{

    private $headers;
    private $acceptKey;
    public $root;
    public $host;
    public $origin;
    public $key;
    public $version;

    /**
     * @param string $headers Headers as string
     */
    public function __construct($headers = null)
    {
        if ($headers) {
            $this->root = $this->getRoot($headers);
            $this->host = $this->getHost($headers);
            $this->origin = $this->getOrigin($headers);
            $this->key = $this->getKey($headers);
            $this->version = $this->getVersion($headers);
        }
    }

    /**
     * Extract root from headers
     * @param string $headers Headers as string
     * @return string|null
     */
    public function getRoot($headers)
    {
        if (preg_match("/GET (.*) HTTP/", $headers, $match))
            return $match[1];

        return null;
    }

    /**
     * Get version of websocket from headers
     * @param string $headers
     * @throws Exception
     * @return int
     */
    public function getVersion($headers)
    {
        if (preg_match("/Sec-WebSocket-Version: (.*)\r\n/", $headers, $match)) {
            return (int) $match[1];
        } else {
            throw new Exception("The client doesn't support WebSocket");
        }
    }

    /**
     * Extract host from headers
     * @param string $headers Headers as string
     * @return string|null
     */
    public function getHost($headers)
    {
        if (preg_match("/Host: (.*)\r\n/", $headers, $match))
            return $match[1];

        return null;
    }

    /**
     * Extract origin from headers
     * @param string $headers Headers as string
     * @return string|null
     */
    public function getOrigin($headers)
    {
        if (preg_match("/Origin: (.*)\r\n/", $headers, $match))
            return $match[1];

        return null;
    }

    /**
     * Extract WebSocket key from headers
     * @param string $headers Headers as string
     * @return string|null
     */
    public function getKey($headers)
    {
        if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $headers, $match))
            return $match[1];

        return null;
    }

    /**
     * Create accept key
     * @throws Exception
     * @return string
     */
    public function getAcceptKey()
    {
        if (!$this->acceptKey) {
            if (!$this->key) {
                throw new \Exception("Missing key");
            }

            $acceptKey = $this->key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
            $acceptKey = base64_encode(sha1($acceptKey, true));

            $this->acceptKey = $acceptKey;
        }

        return $this->acceptKey;
    }

    /**
     * Get upgrade HTTP/1.1 header
     * @return string HTTP/1.1 upgrade header including socket accept key
     */
    public function getUpgradeHeader()
    {
        $upgrade = "HTTP/1.1 101 Switching Protocols\r\n" .
                "Upgrade: websocket\r\n" .
                "Connection: Upgrade\r\n" .
                "Sec-WebSocket-Accept: {$this->getAcceptKey()}" .
                "\r\n\r\n";

        return $upgrade;
    }

}
