<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\Order;
use App\Service\OrderProductService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly OrderProductService $orderProductService,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('quantity', [$this, 'getProductQuantity']),
        ];
    }

    public function getProductQuantity(?Order $order, int $productId): int
    {
        if ($order === null) {
            return 0;
        }

        $orderProduct = $this->orderProductService->findOrderProductForOrder($order, $productId);

        return $orderProduct !== null ? $orderProduct->getQuantity() : 0;
    }
}
