<?php

namespace App\Manager;

use App\Entity\Cart\Order;
use App\Factory\OrderFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    public function __construct(
        private readonly CartSessionStorage $cartSessionStorage,
        private readonly OrderFactory $cartFactory,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Gets the current cart.
     *
     * @return Order
     */
    public function getCurrentCart(): Order
    {
        $cart = $this->cartSessionStorage->getCart();

        if (!$cart) {
            $cart = $this->cartFactory->create();
        }

        return $cart;
    }

    /**
     * @param Order $cart
     * @return void
     */
    public function save(Order $cart): void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        $this->cartSessionStorage->setCart($cart);
    }
}