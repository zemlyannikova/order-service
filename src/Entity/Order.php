<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use App\Workflow\OrderStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::BIGINT, options: [
        'unsigned' => true,
    ])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn()]
    private User $user;

    #[ORM\Column(type: Types::STRING)]
    private string $status = OrderStatus::ShoppingCart;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $userName = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $userAddress = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $userPhone = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $userEmail = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $userTaxNumber = null;

    /**
     * @var Collection<int, OrderProduct>
     */
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderProduct::class, cascade: ['persist', 'remove'])]
    private Collection $orderProducts;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->orderProducts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserAddress(): ?string
    {
        return $this->userAddress;
    }

    public function setUserAddress(?string $userAddress): self
    {
        $this->userAddress = $userAddress;

        return $this;
    }

    public function getUserPhone(): ?string
    {
        return $this->userPhone;
    }

    public function setUserPhone(?string $userPhone): self
    {
        $this->userPhone = $userPhone;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(?string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getUserTaxNumber(): ?string
    {
        return $this->userTaxNumber;
    }

    public function setUserTaxNumber(?string $userTaxNumber): self
    {
        $this->userTaxNumber = $userTaxNumber;

        return $this;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    /**
     * @var Collection<int, OrderProduct> $orderProducts
     */
    public function setOrderProducts(Collection $orderProducts): self
    {
        $this->orderProducts = $orderProducts;

        return $this;
    }
}
