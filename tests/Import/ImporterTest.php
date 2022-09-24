<?php

namespace Oksana2lucky\WarehouseBundle\Tests\Import;

use Oksana2lucky\WarehouseBundle\Import\ResourceInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImporterTest extends KernelTestCase
{
    public function testSomething()
    {
        $request = new Request();
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->assertSame(1, 1);
    }

    private function createResourceInterfaceMock(): ResourceInterface
    {
        $resource = $this->createMock(ResourceInterface::class);
        return $resource;
    }
}