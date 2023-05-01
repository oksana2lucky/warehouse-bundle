<?php


namespace Oksana2lucky\WarehouseBundle\Tests\Import\Data;

use PHPUnit\Framework\TestCase;
use Oksana2lucky\WarehouseBundle\Import\Data\ProductValidator;

class ProductValidatorTest extends TestCase
{
    public function testValidateValid()
    {
        $data = [
            'sku' => 'ABC123',
            'name' => 'Product Name',
            'price' => 10.99,
        ];

        $validator = new ProductValidator();
        $validator->setData($data)
            ->addDataTypeRules()
            ->addValueRules();

        $isValid = $validator->validate();

        $this->assertTrue($isValid);
    }

    public function testValidateInvalid()
    {
        $data = [
            'sku' => 'ABC123',
            'name' => 'Product Name',
            'price' => 'error in report',
        ];

        $validator = new ProductValidator();
        $validator->setData($data)
            ->addDataTypeRules()
            ->addValueRules();

        $isInvalid = $validator->validate();

        $this->assertFalse($isInvalid);
    }
}
