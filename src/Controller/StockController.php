<?php

namespace Oksana2lucky\WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Oksana2lucky\WarehouseBundle\Entity\Stock;

#[Route('/stock')]
class StockController extends AbstractController
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

    #[Route('/', name: 'oksana2lucky_warehouse_stock_index')]
    public function index(): Response
    {
        $stocks = $this->entityManager->getRepository(Stock::class)->findAll();

        return $this->render('@Oksana2luckyWarehouse/stock/index.html.twig', [
            'stocks' => $stocks,
        ]);
    }

    #[Route('/view/{id}', name: 'oksana2lucky_warehouse_stock_view')]
    public function view(int $id): Response
    {
        $stock = $this->entityManager->getRepository(Stock::class)->find($id);

        return $this->render('@Oksana2luckyWarehouse/stock/view.html.twig', [
            'stock' => $stock,
        ]);
    }
}
