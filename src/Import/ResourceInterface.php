<?php

namespace Oksana2lucky\WarehouseBundle\Import;

interface ResourceInterface
{
    /**
     * @param string $filepath
     * @return mixed
     */
    public function init(string $filepath);

    /**
     * @return void
     */
    public function load(): void;
}
