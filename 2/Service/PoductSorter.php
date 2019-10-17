<?php

declare(strict_types=1);

namespace Service\Product\Service;

use Model\Entity\Product;
use Service\Product\Contract\ComparatorInterface;

class ProductSorter
{
    /**
     * @var ComparatorInterface
     */
    private $comparator;

    /**
     * OrderSorter constructor.
     * @param ComparatorInterface $comparator
     */
    public function __construct(ComparatorInterface $comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * @param Product[] $products
     * @return Product[]
     */
    public function sort(array $products): array
    {
        usort($products, [$this->comparator, 'compare']);

        return $products;
    }
}