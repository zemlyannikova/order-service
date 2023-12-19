<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\AccessDeniedException;
use App\Form\Type\OrderAddressType;
use App\Service\OrderAddressService;
use App\Service\OrderService;
use App\Service\OrderWorkflowService;
use App\Workflow\OrderStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    public const ADDRESS_ROUTE = 'address';

    public function __construct(
        private readonly OrderAddressService $orderAddressService,
        private readonly OrderWorkflowService $orderWorkflowService,
        private readonly OrderService $orderService,
    ) {
    }

    #[Route('/orders/{orderId<\d+>}/delivery-address', self::ADDRESS_ROUTE, methods: ['get', 'post'], format: 'html')]
    public function address(int $orderId, Request $request): Response
    {
        if (!$this->orderService->isOrderValid($orderId, [OrderStatus::DeliveryAddress])) {
            throw new AccessDeniedException();
        }

        $orderAddress = $this->orderAddressService->getOrderAddressFormData($orderId);
        $form = $this->createForm(OrderAddressType::class, $orderAddress);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $orderAddress = $form->getData();
            if ($this->orderAddressService->isOrderAddressValid($orderAddress)) {
                $this->orderWorkflowService->confirmAddress($orderId, $orderAddress);

                return $this->redirectToRoute(PurchaseController::SUMMARY_ROUTE, ['orderId' => $orderId]);
            }
        }

        return $this->render('delivery_address.html.twig', [
            'form' => $form,
        ]);
    }
}
