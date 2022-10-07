<?php

namespace Oksana2lucky\WarehouseBundle\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Oksana2lucky\WarehouseBundle\Entity\Product;
use Oksana2lucky\WarehouseBundle\Entity\Stock;
use Oksana2lucky\WarehouseBundle\Entity\ProductStock;

class WarehouseProvider extends Provider
{
    /**
     * @return Query
     */
    public function productsInStocks(): Query
    {
        return $this->entityManager
            ->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->getQuery();
    }
}