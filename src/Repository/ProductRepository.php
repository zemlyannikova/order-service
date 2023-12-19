<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function getPage(int $pageNumber, int $pageSize): array
    {
        $offset = ($pageNumber - 1) * $pageSize;
        return $this->findBy([], limit: $pageSize, offset: $offset);
    }
}
