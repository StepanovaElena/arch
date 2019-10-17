<?php

declare(strict_types=1);

namespace Service\Product\Comparator;


use Model\Entity\Product;
use Service\Product\Contract\ComparatorInterface;

class NameComparator implements ComparatorInterface
{
    public const SORTING_BY_NAME = 'ByName';
    /**
     * @param Product $a
     * @param Product $b
     * @return int
     */
    public function compare($a, $b): int
    {
        return $a->getName() <=> $b->getName();
    }
}