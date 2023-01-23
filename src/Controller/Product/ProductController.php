<?php

namespace App\Controller\Product;

use App\Entity\Product\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/products')]
class ProductController extends AbstractController
{
    #[Route(path: '/', name: 'products_list')]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $result = $doctrine->getRepository(Product::class)->findAll();

        $products = [];

        foreach ($result as $product)
        {
            $products[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice()
            ];
        }

        return $this->json(
            ['products' => $products]
        );
    }

    /**
     * @param int $typeId
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     */
    #[Route(path: '/type/{typeId}', name: 'products_list_by_type')]
    public function productsByType(int $typeId, ManagerRegistry $doctrine): JsonResponse
    {
        $products = $doctrine->getRepository(Product::class)->findByTypeId($typeId);

        return $this->json(
          ['products' => $products]
        );
    }

}