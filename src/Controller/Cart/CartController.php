<?php

namespace App\Controller\Cart;

use App\Entity\Product\Product;
use App\Factory\OrderItemFactory;
use App\Manager\CartManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/cart')]
class CartController extends AbstractController
{
    /**
     * @param CartManager $cartManager
     * @return JsonResponse
     */
    #[Route(path: '/', name: 'cart_index')]
    public function index(CartManager $cartManager): JsonResponse
    {
        $cart = $cartManager->getCurrentCart();

        $total = $cart->getTotal();
        $itemsCount = $cart->getItems()->count();
        $itemList = $cart->getProducts();

        return $this->json(
            [
                'itemsCount' => $itemsCount,
                'total' => $total,
                'itemList' => $itemList,
            ], Response::HTTP_OK
        );
    }

    /**
     * @param Product $product
     * @param CartManager $cartManager
     * @return JsonResponse
     */
    #[Route(path: '/add/{id}', name: 'add_product_to_cart')]
    public function addToCart(Product $product, CartManager $cartManager): JsonResponse
    {
        $cart = $cartManager->getCurrentCart();

        $factory = new OrderItemFactory();

        $item = $factory->createItem($product);

        $cart->addItem($item);

        $cartManager->save($cart);

        $status = 'Product was successfully added to Cart';

        return $this->json($status, Response::HTTP_CREATED);
    }

    /**
     * @param Product $product
     * @param CartManager $cartManager
     * @return JsonResponse
     */
    #[Route('/delete/{id}', name: 'delete_product_from_cart')]
    public function deleteFromCart(Product $product, CartManager $cartManager): JsonResponse
    {
        $cart = $cartManager->getCurrentCart();

        if (($item = $cart->getItemByProduct($product)) === NULL) {
            return $this->json('No such product in a Cart', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $cart->removeItem($item);

        $cartManager->save($cart);

        return $this->json('Product was successfully deleted from cart', Response::HTTP_ACCEPTED);
    }

    /**
     * @param CartManager $cartManager
     * @return JsonResponse
     */
    #[Route(path: '/clear', name: 'clear_cart')]
    public function clearCart(CartManager $cartManager): JsonResponse
    {
        $cart = $cartManager->getCurrentCart();

        $cart->removeItems();

        $cartManager->save($cart);

        return $this->json('Cart was cleared');
    }
}