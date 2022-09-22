<?php

namespace Oksana2lucky\WarehouseBundle\Import;

use Oksana2lucky\WarehouseBundle\Import\Data\AbstractHandler;
use Psr\Log\LoggerInterface;

class Importer
{
    /** @var bool|null */
    private ?bool $noDb = false;

    /** @var ResourceInterface */
    private ResourceInterface $resource;

    /** @var AbstractHandler */
    private AbstractHandler $dataHandler;

    /** @var array */
    private array $result;

    /** @var LoggerInterface */
    protected LoggerInterface $logger;

    /**
     * @param ResourceInterface $resource
     * @param AbstractHandler $dataHandler
     * @param LoggerInterface $logger
     */
    public function __construct(ResourceInterface $resource, AbstractHandler $dataHandler, LoggerInterface $logger)
    {
        $this->resource = $resource;
        $this->dataHandler = $dataHandler;
        $this->logger = $logger;
    }

    /**
     * @return AbstractHandler
     */
    public function getDataHandler(): AbstractHandler
    {
        return $this->dataHandler;
    }

    /**
     * @param mixed $source
     * @param bool|null $noDb
     * @return void
     */
    private function init(mixed $source, ?bool $noDb = false): void
    {
        $this->noDb = $noDb;
        $this->resource->init($source);
    }

    /**
     * @param mixed $source
     * @param bool|null $noDb
     * @return void
     */
    public function run(mixed $source, ?bool $noDb = false): void
    {
        $this->init($source, $noDb);
        $this->logger->info('Product Import: Initialized input data.');

        $this->load();
        $this->logger->info('Product Import: read and parsed the file data.');

        $this->dataHandler->remap($this->resource->getData());
        $this->dataHandler->validate();
        $this->result = $this->dataHandler->getValidData();
        $this->logger->info('Product Import: got result data of the import.');

        if (!$this->isNoDBMode()) {
            $this->save();
            $this->logger->info('Product Import: saved parsed data to the database.');
        }
    }

    /**
     * @return void
     */
    private function load(): void
    {
        $this->resource->load();
    }

    /**
     * @return void
     */
    private function save(): void
    {
        $this->dataHandler->save();
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @return bool
     */
    public function isNoDBMode(): bool
    {
        return $this->noDb == true;
    }
}
