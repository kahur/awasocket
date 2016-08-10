<?php

namespace AwaSocket\Server;

/**
 * Description of Runtime
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class Runtime
{

    private $start;
    private $end;
    private $runtime;

    public function __construct()
    {
        $this->start = microtime(true);
    }

    public function getRuntime()
    {
        $actual = microtime(true);

        $runtime = ($actual - $this->start);
        return $runtime;
    }

    public function stop()
    {
        $this->end = microtime();
    }

}
