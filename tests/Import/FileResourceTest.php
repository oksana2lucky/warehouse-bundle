<?php

namespace Oksana2lucky\WarehouseBundle\Tests\Import;

use Oksana2lucky\WarehouseBundle\Import\FileResource;
use Oksana2lucky\WarehouseBundle\Import\Reader\CSVReader;
use PHPUnit\Framework\TestCase;

class FileResourceTest extends TestCase
{
    public function testLoad()
    {
        // Arrange
        $reader = new CSVReader();
        $resource = new FileResource($reader);
        $filepath = __DIR__ . '/test_data.csv';
        echo $filepath;

        // Act
        $resource->init($filepath);
        $resource->load();
        $data = $resource->getData();

        // Assert
        $this->assertEquals(['id', 'name', 'quantity'], $data[0]);
        $this->assertEquals(['1', 'Product A', '10'], $data[1]);
        $this->assertEquals(['2', 'Product B', '20'], $data[2]);
        $this->assertEquals(['3', 'Product C', '30'], $data[3]);
    }
}
