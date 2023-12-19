<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\OrderProductService;
use App\Service\OrderService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductsController extends AbstractController
{
    public const PRODUCTS_ROUTE = 'products';
    public const ADD_PRODUCT_ROUTE = 'add_product';
    public const REMOVE_PRODUCT_ROUTE = 'remove_product';

    public function __construct(
        private readonly ProductService $productService,
        private readonly OrderProductService $orderProductService,
        private readonly OrderService $orderService,
        private readonly UrlGeneratorInterface $router,
    ) {
    }

    #[Route('/products', name: self::PRODUCTS_ROUTE, methods: ['get'], format: 'html')]
    public function products(Request $request): Response
    {
        $products = $this->productService->getProducts();
        $activeOrder = $this->orderService->getActiveOrderForCurrentUser();

        return $this->render('products.html.twig', [
            'products' => $products,
            'activeOrder' => $activeOrder,
        ]);
    }

    #[Route('/products/{productId<\d+>}/add', name: self::ADD_PRODUCT_ROUTE, methods: ['post'], format: 'html')]
    public function addProduct(int $productId, Request $request): Response
    {
        $this->orderProductService->addProduct($productId);

        return $this->redirectBack($request);
    }

    #[Route('/products/{productId<\d+>}/remove', name: self::REMOVE_PRODUCT_ROUTE, methods: ['post'], format: 'html')]
    public function removeProduct(int $productId, Request $request): Response
    {
        $this->orderProductService->removeProduct($productId);

        return $this->redirectBack($request);
    }

    private function redirectBack(Request $request): Response
    {
        $backUrl = $request->get('backUrl', self::PRODUCTS_ROUTE);
        try {
            $this->router->generate($backUrl);
        } catch (RouteNotFoundException $e) {
            $backUrl = self::PRODUCTS_ROUTE;
        }

        return $this->redirectToRoute($backUrl);
    }
}
