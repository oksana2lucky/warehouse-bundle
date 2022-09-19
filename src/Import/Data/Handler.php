<?php
namespace Oksana2lucky\WarehouseBundle\Import\Data;

use Doctrine\ORM\EntityManagerInterface;
use Oksana2lucky\WarehouseBundle\Entity\Product;

class Handler
{
    private array $data;

    private Validator $validator;

    protected EntityManagerInterface $entityManager;

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

    public function getData(): array
    {
        return $this->data;
    }

    public function getParsedData(): array
    {
        return $this->data['parsed'];
    }

    public function getValidData(): array
    {
        return $this->data['valid'];
    }

    public function getFailData(): array
    {
        return $this->data['fail'];
    }

    public function remap(array $data): void
    {
        $fields = array_map(function($item) {
                        return strtolower($item);
                    },
                        array_shift($data)
                    );

        $this->data['parsed'] = array_map(function ($item) use($fields) {

                            $item = array_map(function ($subItem) {
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

    public function validate(): void
    {
        $this->validator->addDataRules();

        foreach($this->getParsedData() as $data) {
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

    public function save(): void
    {
        foreach($this->getValidData() as $item) {
            $product = new Product();
            $product->setSku($item['sku']);
            $product->setName($item['name']);
            $product->setPrice($item['price']);
            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }
    }
}