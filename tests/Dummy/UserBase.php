<?php

namespace Spartan\Service\Test\Dummy;

class UserBase
{
    protected MessageInterface $message;

    public function __construct(MessageInterface $message = null)
    {
        $this->message = $message;
    }

    public function message()
    {
        return $this->message;
    }
}
