<?php

namespace Oksana2lucky\WarehouseBundle\DataFixtures;

use Oksana2lucky\WarehouseBundle\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StockFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $stocks = [
            ['name' => 'Professional Fulfillment Services', 'address' => 'England, Unit 2c Hudson Road'],
            ['name' => 'Gogosend (OGOship)', 'address' => 'Estonia, Liivalao 11'],
            ['name' => 'HUB Logistics Finland', 'address' => 'Finland, Vanha Porvoontie 256b, ovi 3-5'],
        ];

        foreach ($stocks as $stockItem) {
            $stock = new Stock();
            $stock->setName($stockItem['name']);
            $stock->setAddress($stockItem['address']);
            $manager->persist($stock);
        }
        $manager->flush();
    }
}
