<?php

namespace Oksana2lucky\WarehouseBundle\DataFixtures;

use Doctrine\ORM\UnitOfWork;
use Oksana2lucky\WarehouseBundle\Entity\Stock;
use Oksana2lucky\WarehouseBundle\Entity\Product;
use Oksana2lucky\WarehouseBundle\Entity\ProductStock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WarehouseFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $objectManager
     * @return void
     */
    public function load(ObjectManager $objectManager)
    {
        $products = $objectManager->getRepository(Product::class)->findAll();
        $stocks = $objectManager->getRepository(Stock::class)->findAll();

        for ($i = 0; $i < 70; $i++) {
            $stock = $stocks[array_rand($stocks)];
            $product = $products[array_rand($products)];

            $productStock = $objectManager->getRepository(ProductStock::class)
                ->findOneBy(['stock' => $stock, 'product' => $product]);

            if (!$productStock) {
                $productStock = new ProductStock($stock, $product);
                if ($objectManager->getUnitOfWork()->getEntityState($productStock) === UnitOfWork::STATE_NEW) {
                    $productStock->setQuantity(rand(0, 5));
                    $objectManager->persist($productStock);
                }
            }
        }
        $objectManager->flush();
    }

    public function getDependencies()
    {
        return [StockFixtures::class,];
    }
}
