<?php

namespace Oksana2lucky\WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class HomepageController extends AbstractController
{
    const MAX_QUANTITY_IN_STOCK = 3;
    const MAX_TOTAL_QUANTITY = 5;

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        return $this->render('@Oksana2luckyWarehouse/homepage.html.twig', [
            'quantityInStock' => self::MAX_QUANTITY_IN_STOCK,
            'totalQuantity' => self::MAX_TOTAL_QUANTITY,
        ]);
    }
}