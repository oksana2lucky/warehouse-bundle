<?php

namespace Oksana2lucky\WarehouseBundle\Import\Data;

use Oksana2lucky\WarehouseBundle\Entity\Product;

class ProductHandler extends AbstractHandler
{
    /**
     * @return void
     */
    public function validate(): void
    {
        $this->validator
            ->addDataTypeRules()
            ->addValueRules();

        parent::validate();
    }

    /**
     * @return void
     */
    public function save(): void
    {
        foreach ($this->getValidData() as $item) {
            $product = $this->entityManager
                ->getRepository(Product::class)->findOneBy(['sku' => $item['sku']]);

            if ($product == null) {
                $product = new Product();
                $product->setSku($item['sku']);
            }

            $product->setName($item['name']);
            $product->setPrice($item['price']);
            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }
    }
}
