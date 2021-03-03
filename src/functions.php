<?php

/**
 * @param \Psr\Container\ContainerInterface|null $instance
 *
 * @return \Psr\Container\ContainerInterface|\Spartan\Service\Container
 */
function container(Psr\Container\ContainerInterface $instance = null)
{
    static $container = null;

    if ($instance) {
        $container = $instance;
    }

    return $container;
}
