<?php

namespace Oksana2lucky\WarehouseBundle\Tests\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Oksana2lucky\WarehouseBundle\Provider\Provider;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    public function testRun()
    {
        // create a mock EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // create a mock Provider instance with the EntityManagerInterface mock injected
        $provider = $this->getMockBuilder(Provider::class)
            ->setConstructorArgs([$entityManager])
            ->onlyMethods(['run'])
            ->getMock();

        // set expectations for the run method
        $provider->expects($this->once())
            ->method('run')
            ->with('some-action', ['foo' => 'bar']);

        // call the run method
        $provider->run('some-action', ['foo' => 'bar']);
    }

    public function testGetTitle()
    {
        // create a mock EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // create a new Provider instance with the EntityManagerInterface mock injected
        $provider = new Provider($entityManager);

        // set the action property of the Provider instance
        $provider->run('some-action');

        // assert that the getTitle method returns the expected value
        $this->assertEquals('Some Action', $provider->getTitle());
    }
}
