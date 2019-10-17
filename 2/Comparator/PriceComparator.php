<?php

declare(strict_types=1);

namespace Service\Product\Comparator;

use Model\Entity\Product;
use Service\Product\Contract\ComparatorInterface;

class PriceComparator implements ComparatorInterface
{
    public const SORTING_BY_PRICE = 'ByPrice';

    /**
     * @param Product $a
     * @param Product $b
     * @return int
     */
    public function compare($a, $b): int
    {
        return $a->getPrice() <=> $b->getPrice();
    }
}