<?php

declare(strict_types=1);

namespace Arachne\ContainerAdapter;

use Arachne\ContainerAdapter\Exception\ContainerException;
use Arachne\ContainerAdapter\Exception\ServiceNotFoundException;
use Nette\DI\Container;
use Nette\DI\MissingServiceException;
use Psr\Container\ContainerInterface;

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
    public function get($id)
    {
        try {
            return $this->container->getService($id);
        } catch (MissingServiceException $e) {
            throw new ServiceNotFoundException($e);
        } catch (\Exception $e) {
            throw new ContainerException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        return $this->container->hasService($id);
    }
}
