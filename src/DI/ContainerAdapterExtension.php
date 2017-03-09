<?php

namespace Arachne\ContainerAdapter\DI;

use Arachne\ContainerAdapter\ContainerAdapter;
use Nette\DI\CompilerExtension;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ContainerAdapterExtension extends CompilerExtension
{
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('containerAdapter'))
            ->setClass(ContainerAdapter::class);
    }
}
