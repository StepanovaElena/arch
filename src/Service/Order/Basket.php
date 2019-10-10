<?php

declare(strict_types = 1);

namespace Service\Order;

use Model;
use Model\Entity\Product;
use Model\Repository\ProductRepository;
use Service\Billing\Exception\BillingException;
use Service\Billing\BillingInterface;
use Service\Billing\Transfer\Card;
use Service\Communication\Exception\CommunicationException;
use Service\Communication\CommunicationInterface;
use Service\Communication\Sender\Email;
use Service\Discount\DiscountInterface;
use Service\Discount\NullObject;
use Service\Order\Contract\BasketBuilderInterface;
use Service\User\SecurityInterface;
use Service\User\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Basket
{
    /**
     * Сессионный ключ списка всех продуктов корзины
     */
    private const BASKET_DATA_KEY = 'basket';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Добавляем товар в заказ
     * @param int $productId
     * @return void
     */
    public function addProduct(int $productId): void
    {
        $basket = $this->session->get(static::BASKET_DATA_KEY, []);
        if (!in_array($productId, $basket, true)) {
            $basket[] = $productId;
            $this->session->set(static::BASKET_DATA_KEY, $basket);
        }
    }

    /**
     * Проверяем, лежит ли продукт в корзине или нет
     * @param int $productId
     * @return bool
     */
    public function isProductInBasket(int $productId): bool
    {
        return in_array($productId, $this->getProductIds(), true);
    }

    /**
     * Получаем информацию по всем продуктам в корзине
     * @return Product[]
     */
    public function getProductsInfo(): array
    {
        $productIds = $this->getProductIds();
        return $this->getProductRepository()->search($productIds);
    }

    /**
     * @return float
     */
    public function calculateProductsTotalPrice(): float
    {
        $totalPrice = 0;
        foreach ($this->getProductsInfo() as $product) {
            $totalPrice += $product->getPrice();
        }
        return $totalPrice;
    }

    /**
     * Оформление заказа
     * @param BasketBuilderInterface $builder
     * @return void
     * @throws BillingException
     * @throws CommunicationException
     */
    public function checkout(BasketBuilderInterface $builder): void
    {
        $builder->setBilling(new Card())
            ->setDiscount(new NullObject())
            ->setCommunication(new Email());

        // Здесь должна быть некоторая логика выбора способа платежа
        //$billing = new Card();

        // Здесь должна быть некоторая логика получения информации о скидке
        // пользователя
        //$discount = new NullObject();

        // Здесь должна быть некоторая логика получения способа уведомления
        // пользователя о покупке
        //$communication = new Email();
        //$this->checkoutProcess($discount, $billing, $security, $communication);

        $security = new Security($this->session);
        $checkout = new CheckoutProcess($builder, $security);
        $checkout->doCheckout();
    }

    /**
     * Проведение всех этапов заказа
     * @param DiscountInterface $discount
     * @param BillingInterface $billing
     * @param SecurityInterface $security
     * @param CommunicationInterface $communication
     * @return void
     * @throws BillingException
     * @throws CommunicationException
     */
    //public function checkoutProcess(
    //    DiscountInterface $discount,
    //    BillingInterface $billing,
    //    SecurityInterface $security,
    //    CommunicationInterface $communication
    //): void {
    //    $totalPrice = 0;
    //
    //    foreach ($this->getProductsInfo() as $product) {
    //        $totalPrice += $product->getPrice();
    //    }

    //    $discount = $discount->getDiscount();
    //    $totalPrice = $totalPrice - $totalPrice / 100 * $discount;

    //    $billing->pay($totalPrice);

    //    $user = $security->getUser();
    //    $communication->process($user, 'checkout_template');
    //}

    /**
     * Фабричный метод для репозитория Product
     * @return ProductRepository
     *
     * Применене для создания объекта репозитория единожды и последующего использования его методов текущем классе.
     */
    protected function getProductRepository(): ProductRepository
    {
        return new ProductRepository();
    }

    /**
     * Получаем список id товаров корзины
     * @return array
     */
    private function getProductIds(): array
    {
        return $this->session->get(static::BASKET_DATA_KEY, []);
    }
}
