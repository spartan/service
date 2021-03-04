<?php

use Psr\Container\ContainerInterface;
use Spartan\Service\Container;

if (!function_exists('container')) {
    /**
     * @param ContainerInterface|null $instance
     *
     * @return ContainerInterface|Container
     */
    function container(ContainerInterface $instance = null)
    {
        static $container = null;

        if ($instance) {
            $container = $instance;
        }

        return $container;
    }
}
