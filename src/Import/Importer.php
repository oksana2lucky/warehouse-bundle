<?php
namespace Oksana2lucky\WarehouseBundle\Import;

use Oksana2lucky\WarehouseBundle\Import\Data\Handler;

class Importer
{
    private ?bool $noDb = false;

    private ResourceInterface $resource;

    private array $result;

    public function __construct(ResourceInterface $resource, Handler $dataHandler)
    {
        $this->resource = $resource;
        $this->dataHandler = $dataHandler;
    }

    public function getDataHandler(): Handler
    {
        return $this->dataHandler;
    }

    private function init(mixed $source, ?bool $noDb = false)
    {
        $this->noDb = $noDb;
        $this->resource->init($source);
    }

    public function run(mixed $source, ?bool $noDb = false): void
    {
        $this->init($source, $noDb);

        $this->load();

        $this->dataHandler->remap($this->resource->getData());
        $this->dataHandler->validate();
        $this->result = $this->dataHandler->getValidData();

        if (!$this->isNoDBMode()) {
            $this->save();
        }
    }

    private function load(): void
    {
        $this->resource->load();
    }

    private function save(): void
    {
        $this->dataHandler->save();
    }

    public function getResult()
    {
        return $this->result;
    }

    public function isNoDBMode(): bool
    {
        return $this->noDb == true;
    }
}