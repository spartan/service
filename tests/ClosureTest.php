<?php

namespace Spartan\Service\Test;

use PHPUnit\Framework\TestCase;
use Spartan\Service\Container;
use Spartan\Service\Test\Dummy\Message;
use Spartan\Service\Test\Dummy\MessageInterface;
use Spartan\Service\Test\Dummy\User;
use Spartan\Service\Test\Dummy\UserBase;
use Spartan\Service\Test\Dummy\UserCustom;

/**
 * ClosureTest Test
 *
 * @package Spartan\Service
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class ClosureTest extends TestCase
{
    public function testUser()
    {
        $container = new Container([], [], Container::SINGLETON | Container::LOGGING);
        $container->withBindings(
            [
                'message' => MessageInterface::class,
                MessageInterface::class => function($c) {
                    return new Message('template');
                },
            ]
        );

        /*
         * Test string
         */
        $this->assertSame('template', $container->get('message')->template());

        /*
         * Run reflection
         */
        $user = $container->get(User::class);

        $this->assertSame(
            'template',
            $user->message()->template()
        );

        $this->assertSame(
            [
                UserBase::class,
            ],
            $container->logs
        );

        /*
         * Run again and make sure it does not reflect
         */
        $container->get(User::class);

        $this->assertSame(
            [UserBase::class],
            $container->logs
        );

        $container->get(UserCustom::class);

        $this->assertSame(
            [UserBase::class, UserCustom::class],
            $container->logs
        );
    }
}
