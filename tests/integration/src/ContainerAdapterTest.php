<?php

namespace Tests\Integration;

use Arachne\Codeception\Module\NetteDIModule;
use Arachne\ContainerAdapter\ContainerAdapter;
use Codeception\Test\Unit;
use DateTime;
use Nette\DI\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

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

    protected function _before()
    {
        $this->containerAdapter = $this->tester->grabService(ContainerAdapter::class);
    }

    public function testGet()
    {
        $this->assertInstanceOf(Container::class, $this->containerAdapter->get('container'));

        try {
            $this->containerAdapter->get('nonexistent');
            $this->fail();
        } catch (ServiceNotFoundException $e) {
        }

        try {
            $this->containerAdapter->get('nonexistent', ContainerAdapter::EXCEPTION_ON_INVALID_REFERENCE);
            $this->fail();
        } catch (ServiceNotFoundException $e) {
        }

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

        try {
            $this->containerAdapter->getParameter('nonexistent');
            $this->fail();
        } catch (InvalidArgumentException $e) {
        }
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
}
