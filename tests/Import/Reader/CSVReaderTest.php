<?php

namespace Oksana2lucky\WarehouseBundle\Tests\Import\Reader;

use PHPUnit\Framework\TestCase;
use Oksana2lucky\WarehouseBundle\Import\Reader\CSVReader;

class CSVReaderTest extends TestCase
{
    private CSVReader $reader;

    private string $filepath;

    public function setUp(): void
    {
        $this->reader = new CSVReader();
        $this->filepath = __DIR__ . '/test.csv';
    }

    public function testCurrent(): void
    {
        $this->reader->init($this->filepath);
        $this->assertEquals(['a', 'b', 'c'], $this->reader->current());
    }

    public function testKey(): void
    {
        $this->reader->init($this->filepath);
        $this->assertEquals(0, $this->reader->key());
    }

    public function testNext(): void
    {
        $this->reader->init($this->filepath);
        $this->reader->next();
        $this->assertEquals(['1', '2', '3'], $this->reader->current());
    }

    public function testValid(): void
    {
        $this->reader->init($this->filepath);
        $this->assertTrue($this->reader->valid());
    }
}
