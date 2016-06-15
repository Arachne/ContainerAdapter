<?php

namespace Tests\Integration;

use Arachne\Bootstrap\Configurator;
use Arachne\ContainerAdapter\ContainerAdapter;
use Codeception\Test\Unit;
use DateTime;
use IntegrationSuiteTester;
use Nette\DI\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ContainerAdapterTest extends Unit
{
    /**
     * @var IntegrationSuiteTester
     */
    protected $tester;

    /**
     * @var ContainerAdapter
     */
    private $containerAdapter;

    protected function _before()
    {
        $this->containerAdapter = $this->createContainer('config.neon')->getByType(ContainerAdapter::class);
    }

    public function testGet()
    {
        $this->assertInstanceOf(Container::class, $this->containerAdapter->get('container'));

        $this->tester->expectException(ServiceNotFoundException::class, function () {
            $this->containerAdapter->get('nonexistent');
        });

        $this->tester->expectException(ServiceNotFoundException::class, function () {
            $this->containerAdapter->get('nonexistent', ContainerAdapter::EXCEPTION_ON_INVALID_REFERENCE);
        });

        $this->assertNull($this->containerAdapter->get('nonexistent', ContainerAdapter::NULL_ON_INVALID_REFERENCE));
    }

    public function testSet()
    {
        // Add new service.
        $service1 = new DateTime();
        $this->containerAdapter->set('date', $service1);
        $this->assertSame($service1, $this->containerAdapter->get('date'));

        // Replace existing service.
        $service2 = new DateTime();
        $this->containerAdapter->set('date', $service2);
        $this->assertSame($service2, $this->containerAdapter->get('date'));
    }

    public function testHas()
    {
        $this->assertTrue($this->containerAdapter->has('container'));
        $this->assertFalse($this->containerAdapter->has('nonexistent'));
    }

    public function testInitialized()
    {
        $this->assertFalse($this->containerAdapter->initialized('nonexistent'));
        $this->assertFalse($this->containerAdapter->initialized('empty'));
        $this->containerAdapter->get('empty');
        $this->assertTrue($this->containerAdapter->initialized('empty'));
    }

    public function testGetParameter()
    {
        $this->assertFalse($this->containerAdapter->getParameter('debugMode'));

        $this->tester->expectException(InvalidArgumentException::class, function () {
            $this->containerAdapter->getParameter('nonexistent');
        });
    }

    public function testHasParameter()
    {
        $this->assertTrue($this->containerAdapter->hasParameter('debugMode'));
        $this->assertFalse($this->containerAdapter->hasParameter('nonexistent'));
    }

    public function testSetParameter()
    {
        $this->assertFalse($this->containerAdapter->hasParameter('nonexistent'));
        $this->containerAdapter->setParameter('nonexistent', 'value');
        $this->assertTrue($this->containerAdapter->hasParameter('nonexistent'));
        $this->assertSame('value', $this->containerAdapter->getParameter('nonexistent'));
    }

    private function createContainer($file)
    {
        $config = new Configurator();
        $config->setTempDirectory(TEMP_DIR);
        $config->addConfig(__DIR__.'/../config/'.$file);

        return $config->createContainer();
    }
}
