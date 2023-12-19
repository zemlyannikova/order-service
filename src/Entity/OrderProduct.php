<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
class OrderProduct
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn()]
    private Order $order;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn()]
    private Product $product;

    #[ORM\Column(type: Types::INTEGER)]
    private int $quantity = 1;

    public function __construct(Order $order, Product $product)
    {
        $this->order = $order;
        $this->product = $product;
    }

//    public function getId(): int
//    {
//        return $this->id;
//    }
//
//    public function setId(int $id): self
//    {
//        $this->id = $id;
//
//        return $this;
//    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function increaseQuantity(): void
    {
        $this->quantity++;
    }

    public function decreaseQuantity(): void
    {
        $this->quantity--;
    }
}
