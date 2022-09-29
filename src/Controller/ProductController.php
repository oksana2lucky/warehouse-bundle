<?php

namespace Oksana2lucky\WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Oksana2lucky\WarehouseBundle\Entity\Product;

#[Route('/product')]
class ProductController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'oksana2lucky_warehouse_product_index')]
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('@Oksana2luckyWarehouse/product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/{id}', name: 'oksana2lucky_warehouse_product_view')]
    public function view(int $id): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        return $this->render('@Oksana2luckyWarehouse/product/view.html.twig', [
            'product' => $product,
        ]);
    }
}
