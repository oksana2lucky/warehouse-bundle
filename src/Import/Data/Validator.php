<?php
namespace Oksana2lucky\WarehouseBundle\Import\Data;

use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Validator
{
    protected ValidatorInterface $validator;

    protected array $constraints = [];

    protected array $data;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    public function addDataRules(): self
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
                new Assert\PositiveOrZero(),
            ]),
        ]);

        return $this;
    }

    public function validate(): bool
    {
        foreach($this->constraints as $constraint) {
            $violations = $this->validator->validate($this->data, $constraint);
            $resultSuccess = $violations->count() === 0;
            if (!$resultSuccess) {
                break;
            }
        }

        return $resultSuccess ?? false;
    }
}