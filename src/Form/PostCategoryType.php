<?php

namespace TwinElements\PostBundle\Form;

use TwinElements\SeoBundle\Form\Admin\SeoType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use TwinElements\FormExtensions\Type\SaveButtonsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwinElements\AdminBundle\Service\AdminTranslator;
use TwinElements\PostBundle\Entity\PostCategory\PostCategory;

class PostCategoryType extends AbstractType
{
    /**
     * @var AdminTranslator $translator
     */
    private $translator;

    public function __construct(AdminTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label' => $this->translator->translate('post_category.title')
            ])
            ->add('seo', SeoType::class)
            ->add('buttons', SaveButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PostCategory::class,
        ]);
    }
}
