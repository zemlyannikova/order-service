<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Exception\OrderNotFound;
use App\Exception\Unauthorised;
use App\Provider\UserProviderInterface;
use App\Repository\OrderRepository;

class OrderService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly UserProviderInterface $userProvider,
    ) {
    }

    /**
     * @throws OrderNotFound
     */
    public function getOrder(int $orderId): Order
    {
        return $this->orderRepository->getOrder($orderId);
    }

    /**
     * @param string[] $validStatuses
     * @throws Unauthorised
     */
    public function isOrderValid(int $orderId, array $validStatuses): bool
    {
        $order = $this->orderRepository->getOrder($orderId);
        if (!in_array($order->getStatus(), $validStatuses)) {
            return false;
        }

        $user = $this->userProvider->getCurrentUser();
        if ($user !== $order->getUser()) {
            return false;
        }

        return true;
    }
}
