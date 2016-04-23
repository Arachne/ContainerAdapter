<?php

/**
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

namespace Arachne\ContainerAdapter;

use Arachne\ContainerAdapter\Exception\NotSupportedException;
use Nette\DI\Container;
use Nette\DI\MissingServiceException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\IntrospectableContainerInterface;
use Symfony\Component\DependencyInjection\ScopeInterface;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ContainerAdapter implements IntrospectableContainerInterface
{
    /** @var Container */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function set($id, $service, $scope = self::SCOPE_CONTAINER)
    {
        if (func_num_args() >= 3) {
            throw new NotSupportedException();
        }
        $this->container->removeService($id);
        $this->container->addService($id, $service);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        try {
            return $this->container->getService($id);
        } catch (MissingServiceException $e) {
            if ($invalidBehavior === self::EXCEPTION_ON_INVALID_REFERENCE) {
                throw new ServiceNotFoundException($id, null, $e);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->container->hasService($id);
    }

    /**
     * {@inheritdoc}
     */
    public function initialized($id)
    {
        return $this->has($id) && $this->container->isCreated($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        if (!$this->hasParameter($name)) {
            throw new InvalidArgumentException("Parameter $name does not exist.");
        }
        return $this->container->parameters[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        return array_key_exists($name, $this->container->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value)
    {
        $this->container->parameters[$name] = $value;
    }

    public function enterScope($name)
    {
        throw new NotSupportedException();
    }

    public function leaveScope($name)
    {
        throw new NotSupportedException();
    }

    public function addScope(ScopeInterface $scope)
    {
        throw new NotSupportedException();
    }

    public function hasScope($name)
    {
        throw new NotSupportedException();
    }

    public function isScopeActive($name)
    {
        throw new NotSupportedException();
    }
}
