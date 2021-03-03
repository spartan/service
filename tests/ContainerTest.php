<?php

namespace Spartan\Service\Test;

use PHPUnit\Framework\TestCase;
use Spartan\Service\Container;
use Spartan\Service\Test\Dummy\Message;
use Spartan\Service\Test\Dummy\MessageInterface;

/**
 * ContainerTest Test
 *
 * @package Spartan\Service
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class ContainerTest extends TestCase
{
    public function testUser()
    {
        $this->assertTrue((new Container())->has(Message::class));
        $this->assertFalse((new Container())->has(MessageInterface::class));
    }
}
