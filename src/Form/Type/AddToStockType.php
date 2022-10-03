<?php

namespace Oksana2lucky\WarehouseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Oksana2lucky\WarehouseBundle\Entity\Stock;

class AddToStockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stock', EntityType::class, [
                'class' => Stock::class,
                'choice_label' => 'name',
            ])
            ->add('quantity', NumberType::class)
            ->add('save', SubmitType::class)
        ;
    }
}
