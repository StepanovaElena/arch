<?php


namespace Service\Order;


use Service\Billing\BillingInterface;
use Service\Billing\Exception\BillingException;
use Service\Communication\CommunicationInterface;
use Service\Communication\Exception\CommunicationException;
use Service\Discount\DiscountInterface;
use Service\Order\Contract\BasketBuilderInterface;
use Service\User\SecurityInterface;

class CheckoutProcess
{
    /**
     * @var BasketBuilderInterface
     */
    private $builder;

    /**
     * @var SecurityInterface
     */
    private $security;

    /**
     * CheckoutProcess constructor.
     * @param $builder
     * @param SecurityInterface $security
     */
    public function __construct(BasketBuilderInterface $builder, SecurityInterface $security)
    {
        $this->builder = $builder;
        $this->security = $security;
    }

    /**
     * Проведение всех этапов заказа
     * @return void
     * @throws BillingException
     * @throws CommunicationException
     */
    public function doCheckout(): void
    {
        $totalPrice = 0;
        foreach ($this->builder->checkout()->getProductsInfo() as $product) {
            $totalPrice += $product->getPrice();
        }

        $discount = $this->builder->getDiscount()->getDiscount();
        $totalPrice = $totalPrice - $totalPrice / 100 * $discount;

        $this->builder->getBilling()->pay($totalPrice);

        $user = $this->security->getUser();
        $this->builder->getCommunication()->process($user, 'checkout_template');
    }
}