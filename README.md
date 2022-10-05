Symfony Warehouse Bundle
==================

Introduction
------------

The bundle provides warehouse functionality integration for your Symfony Project. It imports product list from csv file to the database. Then you can load testing data via fixtures about stocks and products in those stocks.
Also, it adds product to stock and removes back.

Requirements
-------------------------------------------------------
Symfony ^6.0, PHP ^8.0

Installation
-------------------------------------------------------
1) Install bundle
```bash
$ composer require oksana2lucky/warehouse-bundle
```
2) Add bundles (in config/bundles.php or Kernel):
```php
<?php
// config/bundles.php

return [
//...
    Oksana2lucky\WarehouseBundle\Oksana2luckyWarehouseBundle::class => ['all' => true],,
//...
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Oksana2lucky\WarehouseBundle\Oksana2luckyWarehouseBundle::class => ['all' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['dev' => true, 'test' => true],
];
```

Include routes:
```yml
# config/routes.yaml
blog:
  resource: "@Oksana2luckyWarehouseBundle/Resources/config/routes.yaml"
  prefix: /warehouse

### Post Installation/Configuration actions
1) Import products from specific CSV file:
```bash
bin/console warehouse:import-product -filepath
```

You can import products without saving to the database with option --no-db
```bash
bin/console warehouse:import-product -filepath --no-db
```

Example:
```bash
bin/console warehouse:import-product stock.csv
```

You're supposed to get result like this:
```bash
Products Importer
=================


[INFO] Products have been imported successfully.


All Items: 29

Items imported succesfully: 27

Failed items (2):

A0011, Misc Cables, error in export

A0015, Bluray Player Excellent picture, $4.33
```


2) Update your DB to create necessary warehouse tables
```bash
bin/console make:migration
bin/console doctrine:migrations:migrate
```

3) Load fixtures (optional, --append is mandatory)
```bash
bin/console doctrine:fixtures:load --append
```

Usage
-------------------------------------------------------
The functionality is supposed to be accessible from http://your-project.xyz/warehouse

License
-------
This bundle is released under the MIT license. See the included
[LICENSE](LICENSE) file for more information.