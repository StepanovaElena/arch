<?php


namespace Service\Order;


use Service\Billing\BillingInterface;
use Service\Communication\CommunicationInterface;
use Service\Discount\DiscountInterface;
use Service\Order\Contract\BasketBuilderInterface;

class BasketBuilder implements BasketBuilderInterface
{
    /**
     * @var ?BillingInterface
     */
    private $billing;

    /**
     * @var ?DiscountInterface
     */
    private $discount;

    /**
     * @var ?CommunicationInterface
     */
    private $communication;


    /**
     * @return BillingInterface
     */
    public function getBilling(): ?BillingInterface
    {
        return $this->billing;
    }

    /**
     * @return DiscountInterface
     */
    public function getDiscount(): ?DiscountInterface
    {
        return $this->discount;
    }

     /**
     * @return CommunicationInterface
     */
    public function getCommunication(): ?CommunicationInterface
    {
        return $this->communication;
    }

    /**
     * @param BillingInterface $billing
     * @return BasketBuilderInterface
     */
    public function setBilling(BillingInterface $billing): BasketBuilderInterface
    {
        $this->billing = $billing;
        return $this;
    }

    /**
     * @param DiscountInterface $discount
     * @return BasketBuilderInterface
     */
    public function setDiscount(DiscountInterface $discount): BasketBuilderInterface
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @param CommunicationInterface $communication
     * @return BasketBuilderInterface
     */
    public function setCommunication(CommunicationInterface $communication): BasketBuilderInterface
    {
        $this->communication = $communication;
        return $this;
    }

    /**
     * @return Basket
     */
    public function checkout(): Basket
    {
        return new Basket($this);
    }
}