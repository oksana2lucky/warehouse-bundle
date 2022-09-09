<?php
namespace Oksana2lucky\WarehouseBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class Oksana2luckyWarehouseExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
    }

    public function getAlias(): string
    {
        return 'oksana2lucky_warehouse';
    }
}
