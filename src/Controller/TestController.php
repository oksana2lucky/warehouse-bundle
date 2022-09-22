<?php

namespace Oksana2lucky\WarehouseBundle\Controller;

use Oksana2lucky\WarehouseBundle\Import\Importer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private Importer $importer;

    public function __construct(Importer $importer)
    {
        $this->importer = $importer;
    }

    #[Route('/test', name: 'oksana2lucky_warehouse_test')]
    public function index(): Response
    {
        $this->importer->run('/var/www/html/warehouse/stock.csv', false);
        $result = $this->importer->getResult();

        $skippedData = $this->importer->getDataHandler()->getFailData();

        echo 'All Items: '. count($this->importer->getDataHandler()->getParsedData()). '<br/>';
        echo 'Items imported successfully: '. count($this->importer->getDataHandler()->getValidData()). '<br/>';

        echo 'Failed items ('. count($this->importer->getDataHandler()->getFailData()) .'): '. '<br/>';
        foreach ($skippedData as $skippedItem) {
            echo implode(', ', $skippedItem). '<br/>';
        }

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
