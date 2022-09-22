<?php

namespace Oksana2lucky\WarehouseBundle\Import;

use Oksana2lucky\WarehouseBundle\Import\Reader\ReaderInterface;

class FileResource implements ResourceInterface
{
    /**
     * @var string
     */
    private string $filepath;

    /**
     * @var ReaderInterface
     */
    private ReaderInterface $reader;

    /**
     * @var array
     */
    private array $data;

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param ReaderInterface $reader
     */
    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param string $filepath
     * @return void
     */
    public function init(string $filepath): void
    {
        $this->filepath = $filepath;
        $this->reader->init($this->filepath);
    }

    /**
     * @return void
     */
    public function load(): void
    {
        $this->data = [];

        foreach ($this->reader as $dataItem) {
            $this->data[] = $dataItem;
        }
    }
}
