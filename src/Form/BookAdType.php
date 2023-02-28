<?php

namespace App\Form;

use App\Entity\BookAd;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookAdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre de l\'annonce',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('place', null, [
                'label' => 'Où ?',
            ])
            ->add('book' , null, [
                'label' => 'Votre livre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookAd::class,
        ]);
    }
}
