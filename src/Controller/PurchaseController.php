<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\AccessDeniedException;
use App\Service\OrderService;
use App\Workflow\OrderStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    public const SUMMARY_ROUTE = 'summary';
    public const ORDER_CONFIRMATION_ROUTE = 'order_confirmation';

    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    #[Route('/orders/{orderId<\d+>}/summary', self::SUMMARY_ROUTE, methods: ['get'], format: 'html')]
    public function showSummary(int $orderId): Response
    {
        if (!$this->orderService->isOrderValid($orderId, [OrderStatus::PurchaseSummary])) {
            throw new AccessDeniedException();
        }

        $order = $this->orderService->getOrder($orderId);

        return $this->render('purchase_summary.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/orders/{orderId<\d+>}/success', name: self::ORDER_CONFIRMATION_ROUTE, methods: ['get'], format: 'html')]
    public function showConfirmation(int $orderId): Response
    {
        if (!$this->orderService->isOrderValid($orderId, [OrderStatus::Ordered])) {
            throw new AccessDeniedException();
        }

        return $this->render('purchase_success.html.twig', [
            'orderId' => $orderId,
        ]);
    }
}
