<?php

// src/Entity/Product.php

namespace Oksana2lucky\WarehouseBundle\Entity;

use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;
use Oksana2lucky\WarehouseBundle\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 12, unique: true)]
    private string $sku;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private float $price;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductStock::class)]
    #[ORM\JoinTable(name: 'product_stock')]
    private PersistentCollection|ArrayCollection $productStocks;

    #[ORM\ManyToMany(targetEntity: Stock::class, inversedBy: 'products')]
    private PersistentCollection|ArrayCollection $stocks;

    public function __construct()
    {
        $this->productStocks = new ArrayCollection();
        $this->stocks = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return PersistentCollection
     */
    public function getStocks(): PersistentCollection
    {
        foreach ($this->stocks as &$stock) {
            $quantity = $this->productStocks
                ->filter(function (ProductStock $productStock) use ($stock) {
                    return $productStock->getStock() == $stock;
                })
                ?->first()
                ?->getQuantity();

            $stock->setQuantity($quantity);
        }

        return $this->stocks;
    }

    /**
     * @param Stock $stock
     * @return $this
     */
    public function addStock(Stock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
        }
        return $this;
    }

    /**
     * @param Stock $stock
     * @return $this
     */
    public function removeStock(Stock $stock): self
    {
        $this->stocks->removeElement($stock);
        return $this;
    }
}
