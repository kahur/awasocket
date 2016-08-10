<?php

namespace Tests\Server;

use PHPUnit\Framework\TestCase;

/**
 * Description of RuntimeTest
 *
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class RuntimeTest extends TestCase
{

    public function testRuntime()
    {
        $runtime = new \AwaSocket\Server\Runtime();
        $time = microtime(true);

        sleep(1);

        $end = microtime(true) - $time;
        $time = floor($end);

        $this->assertEquals(1, $time);
    }

}
