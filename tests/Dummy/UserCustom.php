<?php

namespace Spartan\Service\Test\Dummy;

class UserCustom extends UserBase
{
    public function __construct(MessageInterface $message = null)
    {
        parent::__construct($message);
    }
}
