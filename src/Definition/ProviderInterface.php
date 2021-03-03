<?php

namespace Spartan\Service\Definition;

use Psr\Container\ContainerInterface;
use Spartan\Service\Pipeline;

/**
 * ServiceProviderInterface
 *
 * @package Spartan\Container
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
interface ProviderInterface
{
    /**
     * @param ContainerInterface $container
     * @param Pipeline               $handler
     *
     * @return ContainerInterface
     */
    public function process(ContainerInterface $container, Pipeline $handler): ContainerInterface;
}
