<?php

namespace Spartan\Service\Test;

use PHPUnit\Framework\TestCase;
use Spartan\Service\Container;
use Spartan\Service\Exception\ContainerException;
use Spartan\Service\Exception\NotFoundException;
use Spartan\Service\Test\Dummy\MessageInterface;

/**
 * ExceptionTest Test
 *
 * @package Spartan\Service
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class ExceptionTest extends TestCase
{
    public function testContainerException()
    {
        $container = new Container();

        $this->expectException(ContainerException::class);

        $container->reflect(MessageInterface::class);
    }

    public function testNotFoundException()
    {
        $container = new Container();

        $this->expectException(NotFoundException::class);

        $container->reflect('Class\Not\Found');
    }
}
