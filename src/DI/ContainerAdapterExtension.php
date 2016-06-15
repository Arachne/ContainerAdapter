<?php

/*
 * This file is part of the Arachne
 *
 * Copyright (c) Jáchym Toušek (enumag@gmail.com)
 *
 * For the full copyright and license information, please view the file license.md that was distributed with this source code.
 */

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
