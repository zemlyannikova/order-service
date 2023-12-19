<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductService
{
    public const DEFAULT_PAGE_SIZE = 20;

    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {
    }

    /**
     * @return Product[]
     */
    public function getProducts(int $pageNumber = 1, int $pageSize = self::DEFAULT_PAGE_SIZE): array
    {
        return $this->productRepository->getPage($pageNumber, $pageSize);
    }
}
