<?php

namespace Spartan\Service\Test;

use PHPUnit\Framework\TestCase;
use Spartan\Service\Container;
use Spartan\Service\Test\Dummy\Message;
use Spartan\Service\Test\Dummy\MessageInterface;
use Spartan\Service\Test\Dummy\Scalar;
use Spartan\Service\Test\Dummy\User;

/**
 * ReflectTest Test
 *
 * @package Spartan\Service
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class ReflectTest extends TestCase
{
    public function testReflect()
    {
        $this->assertSame(
            [
                'message' => [
                    Container::INJECT  => MessageInterface::class,
                    Container::DEFAULT => null,
                ],
            ],
            (new Container())->reflect(User::class)
        );

        $this->assertSame(
            [
                'template' => [
                    Container::DEFAULT => '',
                ],
            ],
            (new Container())->reflect(Message::class)
        );

        $this->assertSame(
            [
                'text'   => [
                    Container::INJECT => 'string'
                ],
                'number' => [
                    Container::INJECT => 'int'
                ],
                'is'     => [
                    Container::INJECT => 'bool'
                ],
            ],
            (new Container())->reflect(Scalar::class)
        );
    }
}
