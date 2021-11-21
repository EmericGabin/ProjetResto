<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\User;
use App\Entity\Restaurent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $restaurent = $options['restaurent'];
        $builder
            //->add('prixTotal')
            ->add('restaurent', EntityType::class,[
                'class' => Restaurent::class,
                'choice_label' => 'nom',
                'query_builder' => function(EntityRepository $er) use ($restaurent) {
                    return $er->createQueryBuilder('r')
                    ->where('r.id = :id')
                    ->setParameter(':id', $restaurent->getId());
                }
            ])
            ->add('user', EntityType::class,[
                'class' => User::class,
                'choice_label' => 'prenom',
                'multiple'     => true,
                'required' => true,
            ])
            ->add('produits', EntityType::class,[
                'class' => Produit::class,
                'choice_label' => 'nom',
                'multiple'     => true,
                'required' => true,
                'query_builder' => function(EntityRepository $er) use ($restaurent) {
                    return $er->createQueryBuilder('p')
                    ->where('p.restaurent = :id')
                    ->setParameter(':id', $restaurent->getId());
                }
            ])
            ->add('quantite', IntegerType::class,  [
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'restaurent' => null,
        ]);
    }
}
