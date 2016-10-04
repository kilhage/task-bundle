<?php

namespace Glooby\TaskBundle\Annotation;

/**
 * @author Emil Kilhage
 * @Annotation
 */
class Schedule
{
    /**
     * @var array
     */
    private static $map = [
        '@yearly' => '0 0 1 1 *',
        '@annually' => '0 0 1 1 *',
        '@monthly' => '0 0 1 * *',
        '@weekly' => '0 0 * * 0',
        '@daily' => '0 0 * * *',
        '@hourly' => '0 * * * *',
        '@semi_hourly' => '*/30 * * * *',
        '@quarter_hourly' => '*/15 * * * *',
        '*' => '* * * * *',
    ];

    /**
     * @var string
     */
    public $runEvery;

    /**
     * @var bool
     */
    public $active = true;

    /**
     * @var null|int
     */
    public $timeout = 0;

    /**
     * @var int
     */
    public $version = 10;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['value'])) {
            $options['runEvery'] = $options['value'];
            unset($options['value']);
        }

        if (empty($options['runEvery'])) {
            throw new \InvalidArgumentException('Missing property runEvery');
        }

        if (isset(self::$map[$options['runEvery']])) {
            $options['runEvery'] = self::$map[$options['runEvery']];
        }

        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }

            $this->$key = $value;
        }

        if (isset($this->timeout) && !is_numeric($this->timeout)) {
            throw new \InvalidArgumentException('Property "timeout" must be an int');
        }

        if (!is_array($this->params)) {
            throw new \InvalidArgumentException('Property "params" must be an array');
        }
    }
}
