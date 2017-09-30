<?php

declare(strict_types=1);

namespace Arachne\ContainerAdapter\Exception;

use Psr\Container\ContainerExceptionInterface;

class ContainerException extends \Exception implements ContainerExceptionInterface
{
    public function __construct(\Exception $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
    }
}
