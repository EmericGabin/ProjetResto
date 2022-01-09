<?php

namespace App\Form;

use App\Entity\Restaurent;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class RestaurentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $role="ROLE_ADMIN";
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('telephone')
            ->add('users', EntityType::class,[
                'class' => User::class,
                'choice_label' => 'email',
                'multiple'     => true,
                'required' => true,
                'query_builder' => function (EntityRepository $er) use($role){
                    return $er->createQueryBuilder('u')
                    ->where('u.roles LIKE :role')
                    ->setParameter(':role', '%"'.$role.'"%');    
                }, 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Restaurent::class,
        ]);
    }
}
