<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\AddressController;
use App\Controller\PurchaseController;
use App\Form\OrderAddressData;
use App\Workflow\OrderStatus;
use App\Workflow\OrderTransition;

class OrderWorkflowService
{
    public function __construct(
        private readonly OrderTransitionsMaker $orderTransitionsMaker,
        private readonly OrderAddressService $orderAddressService,
        private readonly OrderService $orderService,
    ) {
    }

    public function confirmCart(int $orderId): string
    {
        $order = $this->orderService->getOrder($orderId);
        if ($order->getStatus() === OrderStatus::ShoppingCart) {
            $this->orderTransitionsMaker->makeTransition($orderId, OrderTransition::ConfirmCart);
        }

        if ($order->getStatus() === OrderStatus::PurchaseSummary) {
            return PurchaseController::SUMMARY_ROUTE;
        }

        return AddressController::ADDRESS_ROUTE;
    }

    public function confirmAddress(int $orderId, OrderAddressData $orderAddress): void
    {
        $this->orderTransitionsMaker->makeTransition($orderId, OrderTransition::ConfirmAddress);
        $this->orderAddressService->saveOrderAddressForm($orderId, $orderAddress);
    }

    public function finaliseOrder(int $orderId): void
    {
        $this->orderTransitionsMaker->makeTransition($orderId, OrderTransition::FinaliseOrder);
    }

    public function recheckAddress(int $orderId): void
    {
        $this->orderTransitionsMaker->makeTransition($orderId, OrderTransition::RecheckAddress);
    }
}
