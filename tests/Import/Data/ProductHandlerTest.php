<?php

namespace Oksana2lucky\WarehouseBundle\Tests\Import\Data;

use Oksana2lucky\WarehouseBundle\Repository\ProductRepository;
use Oksana2lucky\WarehouseBundle\Import\Data\ProductHandler;
use Oksana2lucky\WarehouseBundle\Import\Data\ProductValidator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ProductHandlerTest extends TestCase
{
    public function testValidate(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(ProductValidator::class);
        $validator->expects($this->once())
            ->method('addDataTypeRules');
        $validator->expects($this->once())
            ->method('addValueRules');

        $handler = new ProductHandler($entityManager, $validator);
        $handler->validate();
    }

    public function testSave(): void
    {
        $validator = new ProductValidator();

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository
            ->method('findOneBy')
            ->willReturn(null);

        $entityManager
            ->method('getRepository')
            ->willReturn($productRepository);

        $entityManager
            ->expects($this->exactly(2))
            ->method('persist');

        $entityManager
            ->expects($this->exactly(2))
            ->method('flush');

        $handler = new ProductHandler($entityManager, $validator);
        $handler->remap([
            ['SKU', 'Name', 'Price'],
            ['A0027', 'VCR NM89', '1200.03'],
            ['A0028', 'Bluray Player KL987', '1100.04'],
        ]);

        $handler->validate();
        $handler->save();
    }
}
