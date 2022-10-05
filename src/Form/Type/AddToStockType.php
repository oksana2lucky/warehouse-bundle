<?php

namespace Oksana2lucky\WarehouseBundle\Form\Type;

use phpDocumentor\Reflection\PseudoTypes\PositiveInteger;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Oksana2lucky\WarehouseBundle\Entity\Stock;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class AddToStockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stock', EntityType::class, [
                'class' => Stock::class,
                'choice_label' => 'name',
            ])
            ->add('quantity', NumberType::class, [
                'html5' => true,
                'attr' => ['value' => 1],
                'constraints' => [
                        new NotBlank(),
                        new PositiveOrZero(),
                    ]
            ])
            ->add('save', SubmitType::class)
        ;
    }
}
