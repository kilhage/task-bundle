<?php

namespace Glooby\TaskBundle\Tests\Annotation\Schedule;

use Glooby\TaskBundle\Annotation\Schedule;
use PHPUnit\Framework\TestCase;

/**
 * @author Emil Kilhage
 */
class ScheduleTest extends TestCase
{
    public function testSimple()
    {
        $schedule = new Schedule(['value' => '@daily']);

        $this->assertEquals('0 0 * * *', $schedule->interval);
        $this->assertTrue($schedule->active);
    }

    public function testInterval()
    {
        $schedule = new Schedule(['interval' => '@daily']);

        $this->assertEquals('0 0 * * *', $schedule->interval);
        $this->assertTrue($schedule->active);
    }

    public function testIntervalActiveParams()
    {
        $schedule = new Schedule([
            'interval' => '@daily',
            'active' => false,
            'params' => [1],
        ]);

        $this->assertEquals('0 0 * * *', $schedule->interval);
        $this->assertEquals([1], $schedule->params);
        $this->assertFalse($schedule->active);
    }

    public function testParamsNonArray()
    {
        $this->expectException('\InvalidArgumentException');

        new Schedule([
            'interval' => '@daily',
            'active' => false,
            'params' => 'foo',
        ]);
    }

    public function testInvalidProperty()
    {
        $this->expectException('\InvalidArgumentException');

        new Schedule(['fooo' => '@daily']);
    }

    public function testInvalidInterval()
    {
        $this->expectException('\InvalidArgumentException');

        new Schedule(['interval' => 'fdsfds fds']);
    }

    public function testMissingInvalid()
    {
        $this->expectException('\InvalidArgumentException');

        new Schedule([]);
    }

    public function testInvalidTimeout()
    {
        $this->expectException('\InvalidArgumentException');

        new Schedule(['interval' => '@daily', 'timeout' => 'x']);
    }
}
