<?php

namespace Oksana2lucky\WarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class StockProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Stock::class, inversedBy: 'stockProducts')]
    #[ORM\JoinColumn(name: 'stock_id', referencedColumnName: 'id', nullable: false)]
    private Stock $stock;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'stockProducts')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    private Product $product;

    #[ORM\Column(nullable: true, options: ['default' => 0])]
    private int $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
