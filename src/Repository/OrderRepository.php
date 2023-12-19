<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use App\Exception\OrderNotFound;
use App\Workflow\OrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getOrder(int $orderId): Order
    {
        $order = $this->find($orderId);
        if ($order === null) {
            throw new OrderNotFound();
        }

        return $order;
    }

    public function saveOrder(Order $order): void
    {
        if (!$this->getEntityManager()->contains($order)) {
            $this->getEntityManager()->persist($order);
        }

        $this->getEntityManager()->flush();
    }
}
