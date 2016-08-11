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

    /**
     * Get actual running time in seconds
     *
     * @return float
     */
    public function getRuntime()
    {

        $actual = ($this->end) ? $this->end : microtime(true);

        $runtime = ($actual - $this->start);
        return $runtime;
    }

    /**
     * Create endpoint, getRuntime will calculate time from start to this endpoints
     */
    public function stop()
    {
        $this->end = microtime();
    }

}
