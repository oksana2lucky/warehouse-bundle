<?php

namespace Oksana2lucky\WarehouseBundle\Import\Reader;

interface ReaderInterface
{
    /**
     * @return void
     */
    public function parseLine(): void;
}
