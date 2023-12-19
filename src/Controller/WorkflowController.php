<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\AccessDeniedException;
use App\Service\OrderService;
use App\Service\OrderWorkflowService;
use App\Workflow\OrderStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkflowController extends AbstractController
{
    public const CONFIRM_CART_ROUTE = 'confirm_cart';
    public const FINALISE_ORDER_ROUTE = 'finalise_order';
    public const RECHECK_ADDRESS_ROUTE = 'recheck_address';

    public function __construct(
        private readonly OrderWorkflowService $orderWorkflowService,
        private readonly OrderService $orderService,
    ) {
    }

    #[Route('/orders/{orderId<\d+>}/confirm-cart', self::CONFIRM_CART_ROUTE, methods: ['post'], format: 'html')]
    public function confirmCart(int $orderId): Response
    {
        $validStatuses = [
            OrderStatus::ShoppingCart,
            OrderStatus::DeliveryAddress,
            OrderStatus::PurchaseSummary,
        ];
        if (!$this->orderService->isOrderValid($orderId, $validStatuses)) {
            throw new AccessDeniedException();
        }

        $route = $this->orderWorkflowService->confirmCart($orderId);

        return $this->redirectToRoute($route, ['orderId' => $orderId]);
    }

    #[Route('/orders/{orderId<\d+>}/finalise-order', self::FINALISE_ORDER_ROUTE, methods: ['post'], format: 'html')]
    public function finaliseOrder(int $orderId): Response
    {
        if (!$this->orderService->isOrderValid($orderId, [OrderStatus::PurchaseSummary])) {
            throw new AccessDeniedException();
        }

        $this->orderWorkflowService->finaliseOrder($orderId);

        return $this->redirectToRoute(PurchaseController::ORDER_CONFIRMATION_ROUTE, ['orderId' => $orderId]);
    }

    #[Route('/orders/{orderId<\d+>}/recheck_address', self::RECHECK_ADDRESS_ROUTE, methods: ['post'], format: 'html')]
    public function recheckAddress(int $orderId): Response
    {
        if (!$this->orderService->isOrderValid($orderId, [OrderStatus::PurchaseSummary])) {
            throw new AccessDeniedException();
        }

        $this->orderWorkflowService->recheckAddress($orderId);

        return $this->redirectToRoute(AddressController::ADDRESS_ROUTE, ['orderId' => $orderId]);
    }
}
