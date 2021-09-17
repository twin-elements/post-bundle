<?php

namespace TwinElements\PostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwinElements\PostBundle\Entity\Post\SearchPost;

class SearchPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', SearchType::class, [
                'label' => 'Szukaj wpis',
                'required' => false
            ])
            ->add('button', SubmitType::class, [
                'label' => "Szukaj",
                'attr' => [
                    'class' => 'ml-4 btn btn-dark'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchPost::class,
            'method' => 'GET'
        ]);
    }
}
