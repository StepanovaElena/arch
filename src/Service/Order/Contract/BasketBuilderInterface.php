<?php


namespace Service\Order\Contract;

use Service\Billing\BillingInterface;
use Service\Communication\CommunicationInterface;
use Service\Discount\DiscountInterface;
use Service\Order\Basket;

interface BasketBuilderInterface
{
    /**
     * @return BillingInterface
     */
    public function getBilling(): ?BillingInterface;

    /**
     * @return DiscountInterface
     */
    public function getDiscount(): ?DiscountInterface;

    /**
     * @return CommunicationInterface
     */
    public function getCommunication(): ?CommunicationInterface;

    /**
     * @param BillingInterface $billing
     * @return BasketBuilderInterface
     */
    public function setBilling(BillingInterface $billing): self;

    /**
     * @param DiscountInterface $discount
     * @return BasketBuilderInterface
     */
    public function setDiscount(DiscountInterface $discount): self;

    /**
     * @param CommunicationInterface $communication
     * @return BasketBuilderInterface
     */
    public function setCommunication(CommunicationInterface $communication): self;

    /**
     * @return Basket
     */
    public function checkout(): Basket;
}