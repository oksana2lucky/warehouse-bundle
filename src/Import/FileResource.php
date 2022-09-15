<?php
namespace Oksana2lucky\WarehouseBundle\Import;

use Oksana2lucky\WarehouseBundle\Import\Reader\ReaderInterface;

class FileResource implements ResourceInterface
{
    private string $filepath;

    private ReaderInterface $reader;

    private array $data;

    public function getData(): array
    {
        return $this->data;
    }

    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    public function init(string $filepath)
    {
        $this->filepath = $filepath;
        $this->reader->init($this->filepath);
    }

    public function load()
    {
        $this->data = [];

        foreach ($this->reader as $dataItem) {
            $this->data[] = $dataItem;
        }
    }
}