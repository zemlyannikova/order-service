<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OrderProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProduct::class);
    }

    public function saveOrderProduct(OrderProduct $orderProduct): void
    {
        if (!$this->getEntityManager()->contains($orderProduct)) {
            $this->getEntityManager()->persist($orderProduct);
        }

        $this->getEntityManager()->flush();
    }

    public function deleteOrderProduct(OrderProduct $orderProduct): void
    {
        $this->getEntityManager()->remove($orderProduct);
        $this->getEntityManager()->flush();
    }
}
