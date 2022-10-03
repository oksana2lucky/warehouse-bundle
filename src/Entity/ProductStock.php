<?php

namespace Oksana2lucky\WarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oksana2lucky\WarehouseBundle\Repository\ProductStockRepository;

#[ORM\Entity(repositoryClass: ProductStockRepository::class)]
class ProductStock
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Stock::class, cascade: ['persist', 'refresh', 'remove'], inversedBy: 'stockProducts')]
    #[ORM\JoinColumn(name: 'stock_id', referencedColumnName: 'id', nullable: false)]
    private Stock $stock;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class, cascade: ['persist', 'refresh', 'remove'], inversedBy: 'stockProducts')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    private Product $product;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private int $quantity;

    /**
     * @param $stock
     * @param $product
     */
    public function __construct($stock, $product)
    {
        $this->stock = $stock;
        $this->product = $product;
    }

    /**
     * @return Stock
     */
    public function getStock(): Stock
    {
        return $this->stock;
    }

    /**
     * @param Stock $stock
     * @return $this
     */
    public function setStock(Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     * @return $this
     */
    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
