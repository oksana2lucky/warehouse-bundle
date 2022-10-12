<?php

namespace Oksana2lucky\WarehouseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Oksana2lucky\WarehouseBundle\Provider\Provider;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/provide/{action}', name: 'oksana2lucky_warehouse_provider_action')]
class ProviderController extends AbstractController
{
    /**
     * @var Provider
     */
    private Provider $dataProvider;

    /**
     * @param Provider $dataProvider
     */
    public function __construct(Provider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public function __invoke(string $action, Request $request): Response
    {
        $quantity = $request->get('quantity') ? intval($request->get('quantity')) : null;
        $params = $quantity ? ['quantity' => $quantity] : [];

        $this->dataProvider->run($action, $params);

        return $this->render('@Oksana2luckyWarehouse/warehouse.html.twig', [
            'title' => $this->dataProvider->getTitle(),
            'fields' => $this->dataProvider->getFields(),
            'items' => $this->dataProvider->getResult(),
        ]);
    }
}