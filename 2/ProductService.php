<?php

declare(strict_types = 1);

namespace Service\Product;

use Model;
use Model\Entity\Product;
use Model\Repository\ProductRepository;
use Service\Product\Comparator\NameComparator;
use Service\Product\Comparator\PriceComparator;
use Service\Product\Service\ProductSorter;


class ProductService extends ProductRepository
{
    /**
     * Получаем информацию по конкретному продукту
     * @param int $id
     * @return Product|null
     */
    public function getInfo(int $id): ?Product
    {
        $product = $this->getProductRepository()->search([$id]);
        return count($product) ? $product[0] : null;
    }

    /**
     * Получаем все продукты
     * @param string $sortType
     * @return Product[]
     */
    public function getAll(string $sortType): array
    {
        $productList = $this->getProductRepository()->fetchAll();

        switch ($sortType) {
            case NameComparator::SORTING_BY_NAME:
                $sorter = new ProductSorter(new NameComparator());
                $sortedArray = $sorter->sort($productList);
                return $sortedArray;
                break;
            case PriceComparator::SORTING_BY_PRICE:
                $sorter = new ProductSorter(new PriceComparator());
                $sortedArray = $sorter->sort($productList);
                return $sortedArray;
                break;
            default:
                return $productList;
        }

        // Применить паттерн Стратегия
        // $sortType === 'price'; // Сортировка по цене
        // $sortType === 'name'; // Сортировка по имени
    }

    /**
     * Фабричный метод для репозитория Product
     * @return ProductRepository
     */
    protected function getProductRepository(): ProductRepository
    {
        return new ProductRepository();
    }

    private function renderOrders(array $orders)
    {
        foreach ($orders as $order) {
            echo "id: {$order->getId()}\tsum: {$order->getSum()}" .
                "\tcreated at: {$order->getCreatedAt()->format('Y-m-d')}\n";
        }
    }
}
