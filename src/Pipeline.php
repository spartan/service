<?php

namespace Spartan\Service;

use Psr\Container\ContainerInterface;

/**
 * Services Pipeline
 *
 * @package Spartan\Service
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class Pipeline
{
    /**
     * @var mixed[]
     */
    protected array $services = [];

    /**
     * Pipeline constructor.
     *
     * @param mixed[] $services
     */
    public function __construct(array $services)
    {
        $this->services = $services;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return ContainerInterface
     */
    public function handle(ContainerInterface $container): ContainerInterface
    {
        if (count($this->services) == 0) {
            return $container;
        }

        $service = array_shift($this->services);

        return $service instanceof \Closure
            ? $service($container, $this)
            : (new $service())->process($container, $this);
    }
}
