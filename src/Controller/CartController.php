<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public const CART_ROUTE = 'cart';

    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    #[Route('/cart', name: self::CART_ROUTE, methods: ['get'], format: 'html')]
    public function cart(Request $request): Response
    {
        $order = $this->orderService->getActiveOrderForCurrentUser();

        return $this->render('cart.html.twig', [
            'order' => $order,
        ]);
    }
}
