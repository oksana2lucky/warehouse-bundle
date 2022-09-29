<?php

namespace Oksana2lucky\WarehouseBundle\Import\Data;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractHandler
{
    /**
     * @var array[]
     */
    private array $data;

    /**
     * @var Validator
     */
    protected Validator $validator;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Validator $validator
     */
    public function __construct(EntityManagerInterface $entityManager, Validator $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->data = [
                'parsed' => [],
                'valid' => [],
                'fail' => [],
            ];
    }

    /**
     * @return array[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getParsedData(): array
    {
        return $this->data['parsed'];
    }

    /**
     * @return array
     */
    public function getValidData(): array
    {
        return $this->data['valid'];
    }

    /**
     * @return array
     */
    public function getFailData(): array
    {
        return $this->data['fail'];
    }

    /**
     * @param array $data
     * @return void
     */
    public function remap(array $data): void
    {
        $fields = array_map(
            function ($item) {
                return strtolower($item);
            },
            array_shift($data)
        );

        $this->data['parsed'] = array_map(
            function ($item) use ($fields) {
                $item = array_map(
                    function ($subItem) {
                        return is_numeric($subItem) ?
                            (float)number_format((float)$subItem, 2, '.', '') :
                            $subItem;
                    },
                    $item
                );

                return array_combine($fields, array_slice($item, 0, count($fields)));
            },
            $data
        );
    }

    /**
     * @return void
     */
    public function validate(): void
    {
        foreach ($this->getParsedData() as $data) {
            $validated = $this->validator
                ->setData($data)
                ->validate();

            if ($validated) {
                $this->data['valid'][] = $data;
            } else {
                $this->data['fail'][] = $data;
            }
        }
    }

    /**
     * @return void
     */
    abstract public function save(): void;
}
