<?php

namespace App\Form;

use App\Entity\LigneCommande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Commande;
use App\Entity\Produit;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LigneCommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $commande = $options['commande'];
        $builder
            ->add('quantite')
            ->add('commande', EntityType::class,[
                'class' => Commande::class,
                'choice_label' => 'id',
                'query_builder' => function(EntityRepository $er) use ($commande) {
                    return $er->createQueryBuilder('c')
                    ->where('c.id = :id')
                    ->setParameter(':id', $commande->getId());
                }
            ])
            ->add('produit', EntityType::class,[
                'class' => Produit::class,
                'choice_label' => 'nom',
                'required' => true,
                'query_builder' => function(EntityRepository $er) use ($commande) {
                    return $er->createQueryBuilder('p')
                    ->where('p.restaurent = :id')
                    ->setParameter(':id', $commande->getRestaurent()->getId());
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LigneCommande::class,
            'commande' => null,
        ]);
    }
}
