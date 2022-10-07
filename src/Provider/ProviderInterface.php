<?php

namespace Oksana2lucky\WarehouseBundle\Provider;

interface ProviderInterface
{
    /**
     * @param string $action
     * @return void
     */
    public function run(string $action): void;

    /**
     * @return array|null
     */
    public function getResult(): ?array;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return array|null
     */
    public function getFields(): ?array;
}