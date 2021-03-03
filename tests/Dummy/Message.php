<?php

namespace Spartan\Service\Test\Dummy;

class Message implements MessageInterface
{
    protected string $template = '';

    public function __construct($template = '')
    {
        $this->template = $template;
    }

    public function template()
    {
        return $this->template;
    }

    public function send()
    {
    }
}
