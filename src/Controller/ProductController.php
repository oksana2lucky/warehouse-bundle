<?php

namespace Oksana2lucky\WarehouseBundle\Controller;

use Oksana2lucky\WarehouseBundle\Entity\ProductStock;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Oksana2lucky\WarehouseBundle\Entity\Product;
use Oksana2lucky\WarehouseBundle\Entity\Stock;
use Oksana2lucky\WarehouseBundle\Entity\StockProduct1;
use Oksana2lucky\WarehouseBundle\Form\Type\AddToStockType;

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

    #[Route('/view/{id}', name: 'oksana2lucky_warehouse_product_view')]
    public function view(Request $request, int $id): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);

        $stock = new Stock();
        $form = $this->createForm(AddToStockType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stock = $form->getData()['stock'] ?? null;
            $quantity = $form->getData()['quantity'] ?? null;
            if ($stock) {
                $product->addStock($stock);
                $this->entityManager->persist($product);
                $this->entityManager->flush();

                $productStock = $this->entityManager->getRepository(ProductStock::class)
                    ->findOneBy(['product' => $product, 'stock' => $stock]);
                $productStock->setQuantity($quantity);
                $this->entityManager->persist($productStock);
                $this->entityManager->flush();

                return $this->redirectToRoute('oksana2lucky_warehouse_product_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('@Oksana2luckyWarehouse/product/view.html.twig', [
            'product' => $product,
            'form' => $form,
            'stocks' => $product->getStocks(),
        ]);
    }

    #[Route('/{id}/removeStock/{stockId}', name: 'oksana2lucky_warehouse_product_removeStock')]
    public function removeStock(Request $request, int $id, int $stockId): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        $stock = $this->entityManager->getRepository(Stock::class)->find($stockId);
        $product->removeStock($stock);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->redirectToRoute('oksana2lucky_warehouse_product_view', ['id' => $id], Response::HTTP_SEE_OTHER);
    }
}
