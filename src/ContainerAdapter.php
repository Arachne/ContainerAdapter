<?php

namespace Arachne\ContainerAdapter;

use Nette\DI\Container;
use Nette\DI\MissingServiceException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ContainerAdapter implements ContainerInterface
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function set($id, $service)
    {
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
}
