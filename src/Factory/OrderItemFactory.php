<?php

namespace App\Factory;

use App\Entity\Cart\OrderItem;
use App\Entity\Product\Product;

class OrderItemFactory
{
    /**
     * Creates an item for a product.
     *
     * @param Product $product
     *
     * @return OrderItem
     */
    public function createItem(Product $product): OrderItem
    {
        $item = new OrderItem();

        $item->setProduct($product);
        $item->setQuantity(1);

        return $item;
    }

}