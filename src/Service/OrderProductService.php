<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Exception\OrderNotFound;
use App\Exception\ProductNotFound;
use App\Exception\Unauthorised;
use App\Provider\UserProviderInterface;
use App\Repository\OrderRepository;
use App\Repository\OrderProductRepository;
use App\Repository\ProductRepository;

class OrderProductService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly OrderRepository $orderRepository,
        private readonly OrderProductRepository $orderProductRepository,
        private readonly UserProviderInterface $userProvider,
    ) {
    }

    /**
     * @throws ProductNotFound
     * @throws Unauthorised
     */
    public function addProduct(int $productId): void
    {
        $user = $this->userProvider->getCurrentUser();
        $product = $this->productRepository->getProduct($productId);
        $order = $this->orderRepository->getActiveOrder($user);
        if ($order === null) {
            $order = new Order($user);
        }

        $orderProduct = $this->findOrderProductForOrder($order, $productId);
        if ($orderProduct === null) {
            $orderProduct = new OrderProduct($order, $product);
            $order->getOrderProducts()->add($orderProduct);
        } else {
            $orderProduct->increaseQuantity();
        }

        $this->orderRepository->saveOrder($order);
    }

    /**
     * @throws OrderNotFound
     * @throws Unauthorised
     */
    public function removeProduct(int $productId): void
    {
        $user = $this->userProvider->getCurrentUser();
        $order = $this->orderRepository->getActiveOrder($user);
        if ($order === null) {
            throw new OrderNotFound();
        }

        $orderProduct = $this->findOrderProductForOrder($order, $productId);
        if ($orderProduct === null) {
            return;
        }
        $orderProduct->decreaseQuantity();
        if ($orderProduct->getQuantity() < 1) {
            $this->orderProductRepository->deleteOrderProduct($orderProduct);
        } else {
            $this->orderProductRepository->saveOrderProduct($orderProduct);
        }
    }

    public function findOrderProductForOrder(Order $order, int $productId): ?OrderProduct
    {
        foreach ($order->getOrderProducts() as $orderProduct) {
            if ($orderProduct->getProduct()->getId() === $productId) {
                return $orderProduct;
            }
        }

        return null;
    }
}
