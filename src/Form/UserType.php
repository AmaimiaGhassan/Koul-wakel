<?php

namespace App\Form;

use App\Entity\Mealplan;
use App\Entity\User;
use App\Entity\Workoutplan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('roles')
            ->add('password')
            ->add('plans', EntityType::class, [
                'class' => Workoutplan::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('meals', EntityType::class, [
                'class' => Mealplan::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
