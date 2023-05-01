<?php

namespace Oksana2lucky\WarehouseBundle\Tests\Import;

use Oksana2lucky\WarehouseBundle\Import\Data\ProductHandler;
use Oksana2lucky\WarehouseBundle\Import\Importer;
use Oksana2lucky\WarehouseBundle\Import\FileResource;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImporterTest extends KernelTestCase
{
    private function createImporter(
        FileResource $resource,
        ProductHandler $dataHandler,
        LoggerInterface $logger): Importer
    {
        return new Importer($resource, $dataHandler, $logger);
    }

    public function testRunNoDb()
    {
        $resource = $this->createMock(FileResource::class);
        $dataHandler = $this->createMock(ProductHandler::class);
        $logger = $this->createMock(LoggerInterface::class);
        $importer = $this->createImporter($resource, $dataHandler, $logger);

        $logger
            ->expects(self::exactly(3))
            ->method('info');

        $dataHandler
            ->expects(self::once())
            ->method('remap');

        $dataHandler
            ->expects(self::once())
            ->method('validate');

        $dataHandler
            ->expects(self::once())
            ->method('getValidData');

        $importer->run('stock.csv', true);

    }

    public function testRunDb()
    {
        $resource = $this->createMock(FileResource::class);
        $dataHandler = $this->createMock(ProductHandler::class);
        $logger = $this->createMock(LoggerInterface::class);
        $importer = $this->createImporter($resource, $dataHandler, $logger);

        $logger
            ->expects(self::exactly(4))
            ->method('info');

        $dataHandler
            ->expects(self::once())
            ->method('remap')
            ->with($resource->getData());

        $dataHandler
            ->expects(self::once())
            ->method('validate');

        $dataHandler
            ->expects(self::once())
            ->method('getValidData');

        $importer->run('stock.csv');
    }
}