<?php

namespace Oksana2lucky\WarehouseBundle\Provider;

use Doctrine\ORM\Query;
use Oksana2lucky\WarehouseBundle\Entity\Product;
use Oksana2lucky\WarehouseBundle\Entity\Stock;
use Oksana2lucky\WarehouseBundle\Entity\ProductStock;

class WarehouseProvider extends Provider
{

    /**
     * @param array|null $filters
     * @return Query
     */
    public function productsPerStock(?array $filters = null): Query
    {
        /*
        product per stock

        select p.id, p.sku, p.name, s.name, ps.quantity from product p
        inner join product_stock ps on p.id = ps.product_id
        inner join stock s on s.id = ps.stock_id
        order by p.sku;

        product per stock with quantity > n

        select p.id, p.sku, p.name, s.name, ps.quantity from product p
        inner join product_stock ps on p.id = ps.product_id
        inner join stock s on s.id = ps.stock_id
        where ps.quantity > n
        order by p.sku;

        */

        $quantity = $filters['quantity'] ?? null;

        $qb = $this->entityManager
            ->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->select(['p.id', 'p.sku', 'p.name as productName', 'p.price', 's.name as stockName', 'ps.quantity'])
            ->innerJoin(ProductStock::class, 'ps')
            ->innerJoin(Stock::class, 's')
            ->where('p.id = ps.product')
            ->andWhere('s.id = ps.stock')
            ->orderBy('p.sku', 'ASC');

        if ($quantity && is_int($quantity)) {
            $qb->andWhere('ps.quantity > :quantity')
                ->setParameter('quantity', $quantity);
        }

        return $qb->getQuery();
    }

    /**
     * @param array|null $filters
     * @return Query
     */
    public function productsInStocks(?array $filters = null): Query
    {
        /*
        products total number in stocks

        select p.id, p.sku, p.name, sum(ps.quantity) from product p
        inner join product_stock ps on p.id = ps.product_id
        group by p.sku
        order by p.sku;

        products total number in stocks
        with total quantity > 5

        select p.id, p.sku, p.name, sum(ps.quantity) as sumQuantity from product p
        inner join product_stock ps on p.id = ps.product_id
        group by p.sku
        having sumQuantity > 5
        order by p.sku;
         */

        $quantity = $filters['quantity'] ?? null;

        $qb = $this->entityManager
            ->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->select(['p.id', 'p.sku', 'p.name', 'p.price', 'SUM(ps.quantity) as sumQuantity'])
            ->innerJoin(ProductStock::class, 'ps')
            ->where('p.id = ps.product')
            ->groupBy('p.sku')
            ->orderBy('p.sku', 'ASC');


        if ($quantity && is_int($quantity)) {
            $qb->having($qb->expr()->gt('sumQuantity', $quantity));
        }

        return $qb->getQuery();
    }

    /**
     * @return Query
     */
    public function productsOutOfStock(): Query
    {
        /*
        products out of stock

        select * from product p
        where not exists(select product_id from product_stock ps
        where quantity > 0 and ps.product_id = p.id);
         */

        $subQb = $this->entityManager
            ->createQueryBuilder()
            ->select('ps')
            ->from(ProductStock::class, 'ps')
            ->where('ps.quantity > 0')
            ->andWhere('ps.product = p');

        $qb = $this->entityManager
            ->createQueryBuilder('p');

        return $qb
            ->select(['p.id', 'p.sku', 'p.name', 'p.price'])
            ->from(Product::class, 'p')
            ->where($qb->expr()->not($qb->expr()->exists($subQb->getDQL())))
            ->getQuery();
    }
}