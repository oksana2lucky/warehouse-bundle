<?php

namespace Oksana2lucky\WarehouseBundle\Import\Data;

use Symfony\Component\Validator\Constraints as Assert;

class ProductValidator extends Validator
{
    /**
     * @return $this
     */
    public function addDataTypeRules(): self
    {
        $this->constraints[] = new Assert\Collection([
            'sku' => new Assert\Required([
                new Assert\Type('string'),
                new Assert\NotBlank(),
            ]),
            'name' => new Assert\Required([
                new Assert\Type('string'),
                new Assert\NotBlank(),
            ]),
            'price' => new Assert\Required([
                new Assert\Type(['int', 'float']),
                new Assert\NotBlank(),
            ]),
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function addValueRules(): self
    {
        $this->constraints[] = new Assert\PositiveOrZero(['price']);

        return $this;
    }
}
