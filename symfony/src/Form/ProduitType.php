<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Restaurent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $restaurent = $options['restaurent'];
        $builder
            ->add('nom')
            ->add('prix')
            ->add('description')
            ->add('restaurent', EntityType::class,[
                'class' => Restaurent::class,
                'choice_label' => 'nom',
                'query_builder' => function(EntityRepository $er) use ($restaurent) {
                    return $er->createQueryBuilder('r')
                    ->where('r.id = :id')
                    ->setParameter(':id', $restaurent->getId());
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'restaurent' => null,
        ]);
    }
}
