<?php
namespace Oksana2lucky\WarehouseBundle\Import;

class Importer
{
    private ?bool $noDb = false;

    private ResourceInterface $resource;

    private array $result;

    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
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

        if (!$noDb) {
            $this->save();
        }

        $this->result = $this->resource->getData();
    }

    private function load(): void
    {
        $this->resource->load();
    }

    private function save(): void
    {

    }

    public function getResult()
    {
        return $this->result;
    }
}