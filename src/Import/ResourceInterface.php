<?php
namespace Oksana2lucky\WarehouseBundle\Import;

interface ResourceInterface
{
    public function init(string $filepath);

    public function load();
}