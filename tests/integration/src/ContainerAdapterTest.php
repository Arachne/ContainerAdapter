<?php

namespace Tests\Integration;

use Arachne\Bootstrap\Configurator;
use Arachne\ContainerAdapter\ContainerAdapter;
use Arachne\ContainerAdapter\Exception\NotSupportedException;
use Codeception\TestCase\Test;
use Codeception\Util\Stub;
use DateTime;
use Nette\DI\Container;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\ScopeInterface;
use VladaHejda\AssertException;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class ContainerAdapterTest extends Test
{
    use AssertException;

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

        $this->assertException(function () {
            $this->containerAdapter->get('nonexistent');
        }, ServiceNotFoundException::class);

        $this->assertException(function () {
            $this->containerAdapter->get('nonexistent', ContainerAdapter::EXCEPTION_ON_INVALID_REFERENCE);
        }, ServiceNotFoundException::class);

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

        $this->assertException(function () {
            $this->containerAdapter->set('date', new DateTime(), 'scope');
        }, NotSupportedException::class);
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

        $this->assertException(function () {
            $this->containerAdapter->getParameter('nonexistent');
        }, InvalidArgumentException::class);
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

    public function testEnterScope()
    {
        $this->assertException(function () {
            $this->containerAdapter->enterScope('scope');
        }, NotSupportedException::class);
    }

    public function testLeaveScope()
    {
        $this->assertException(function () {
            $this->containerAdapter->leaveScope('scope');
        }, NotSupportedException::class);
    }

    public function testAddScope()
    {
        $this->assertException(function () {
            $this->containerAdapter->addScope(Stub::makeEmpty(ScopeInterface::class));
        }, NotSupportedException::class);
    }

    public function testHasScope()
    {
        $this->assertException(function () {
            $this->containerAdapter->hasScope('scope');
        }, NotSupportedException::class);
    }

    public function testIsScopeActive()
    {
        $this->assertException(function () {
            $this->containerAdapter->isScopeActive('scope');
        }, NotSupportedException::class);
    }

    private function createContainer($file)
    {
        $config = new Configurator();
        $config->setTempDirectory(TEMP_DIR);
        $config->addConfig(__DIR__ . '/../config/' . $file, false);
        return $config->createContainer();
    }
}
