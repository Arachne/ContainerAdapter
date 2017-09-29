<?php

namespace Arachne\ContainerAdapter\DI;

use Arachne\ContainerAdapter\ContainerAdapter;
use Nette\DI\CompilerExtension;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ContainerAdapterExtension extends CompilerExtension
{
    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('containerAdapter'))
            ->setType(ContainerAdapter::class);
    }
}
