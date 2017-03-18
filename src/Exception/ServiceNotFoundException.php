<?php

namespace Arachne\ContainerAdapter\Exception;

use Nette\DI\MissingServiceException;
use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
    public function __construct(MissingServiceException $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
    }
}
