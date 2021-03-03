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
 * CreateTest Test
 *
 * @package Spartan\Service
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class CreateTest extends TestCase
{
    public function testUser()
    {
        $container = new Container([], [], Container::SINGLETON | Container::LOGGING);
        $container->withBindings(
            [
                MessageInterface::class => new Message('template'),
            ]
        );

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

    public function testMessage()
    {
        $message = (new Container())->get(Message::class);

        $this->assertSame(
            get_class($message),
            Message::class
        );
    }
}
