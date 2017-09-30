<?php

declare(strict_types=1);

namespace Arachne\ContainerAdapter\Exception;

use Psr\Container\ContainerExceptionInterface;

class InvalidArgumentException extends \InvalidArgumentException implements ContainerExceptionInterface
{
}
