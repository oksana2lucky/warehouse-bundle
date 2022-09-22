<?php

namespace Oksana2lucky\WarehouseBundle\Import\Data;

use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

abstract class Validator
{
    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @var array
     */
    protected array $constraints = [];

    /**
     * @var array
     */
    protected array $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        foreach ($this->constraints as $constraint) {
            $violations = $this->validator->validate($this->data, $constraint);
            $resultSuccess = $violations->count() === 0;
            if (!$resultSuccess) {
                break;
            }
        }

        return $resultSuccess ?? false;
    }
}
