<?php

namespace Oksana2lucky\WarehouseBundle\Provider;

use Doctrine\ORM\EntityManagerInterface;

class Provider implements ProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var string
     */
    protected string $action;

    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var array
     */
    protected array $fields = [];

    /**
     * @var array|null
     */
    protected ?array $result = [];

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $action
     * @return void
     */
    public function run(string $action): void
    {
        $this->action = $action;
        $method = lcfirst(str_replace('-', '', ucwords($this->action, '-')));
        $this->result = call_user_func(array($this, $method))?->getArrayResult();
        $this->fields = isset($this->result[0]) ? array_keys($this->result[0]) : [];
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call(string $name, array $arguments = [])
    {
        $this->result = [];
    }

    /**
     * @return array|null
     */
    public function getResult(): ?array
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return ucwords(str_replace('-', ' ', $this->action));
    }

    /**
     * @return array|null
     */
    public function getFields(): ?array
    {
        return $this->fields;
    }
}