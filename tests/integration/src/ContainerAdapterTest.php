<?php

namespace Tests\Integration;

use Arachne\Codeception\Module\NetteDIModule;
use Arachne\ContainerAdapter\ContainerAdapter;
use Arachne\ContainerAdapter\Exception\ServiceNotFoundException;
use Codeception\Test\Unit;
use Nette\DI\Container;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ContainerAdapterTest extends Unit
{
    /**
     * @var NetteDIModule
     */
    protected $tester;

    /**
     * @var ContainerAdapter
     */
    private $containerAdapter;

    protected function _before(): void
    {
        $this->containerAdapter = $this->tester->grabService(ContainerAdapter::class);
    }

    public function testGet(): void
    {
        $this->assertInstanceOf(Container::class, $this->containerAdapter->get('container'));

        try {
            $this->containerAdapter->get('nonexistent');
            $this->fail();
        } catch (ServiceNotFoundException $e) {
        }
    }

    public function testHas(): void
    {
        $this->assertTrue($this->containerAdapter->has('container'));
        $this->assertFalse($this->containerAdapter->has('nonexistent'));
    }
}
