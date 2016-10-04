<?php

namespace Glooby\TaskBundle\Tests\Annotation\Schedule;

use Glooby\TaskBundle\Annotation\Schedule;

/**
 * @author Emil Kilhage
 */
class ScheduleTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $schedule = new Schedule(['value' => '@daily']);

        $this->assertEquals('0 0 * * *', $schedule->runEvery);
        $this->assertTrue($schedule->active);
    }

    public function testInterval()
    {
        $schedule = new Schedule(['runEvery' => '@daily']);

        $this->assertEquals('0 0 * * *', $schedule->runEvery);
        $this->assertTrue($schedule->active);
    }

    public function testIntervalActiveParams()
    {
        $schedule = new Schedule([
            'runEvery' => '@daily',
            'active' => false,
            'params' => [1],
        ]);

        $this->assertEquals('0 0 * * *', $schedule->runEvery);
        $this->assertEquals([1], $schedule->params);
        $this->assertFalse($schedule->active);
    }

    public function testParamsNonArray()
    {
        $this->setExpectedException('\InvalidArgumentException');

        new Schedule([
            'interval' => '@daily',
            'active' => false,
            'params' => 'foo',
        ]);
    }

    public function testInvalidProperty()
    {
        $this->setExpectedException('\InvalidArgumentException');

        new Schedule(['fooo' => '@daily']);
    }

    public function testInvalidInterval()
    {
        $this->setExpectedException('\InvalidArgumentException');

        new Schedule(['runEvery' => 'fdsfds fds']);
    }

    public function testMissingInvalid()
    {
        $this->setExpectedException('\InvalidArgumentException');

        new Schedule([]);
    }

    public function testInvalidTimeout()
    {
        $this->setExpectedException('\InvalidArgumentException');

        new Schedule(['interval' => '@daily', 'timeout' => 'x']);
    }
}
