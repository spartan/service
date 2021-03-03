<?php

namespace Spartan\Service\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * NotFoundException Exception
 *
 * @package Spartan\Service
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
}
