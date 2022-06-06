<?php


namespace TwinElements\PostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwinElements\Component\AdminTranslator\AdminTranslator;
use TwinElements\FormExtensions\Type\TEEntityType;
use TwinElements\PostBundle\Entity\PostTag\PostTag;

class PostTagEntryType extends AbstractType
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
            ->add('tags', TEEntityType::class, [
                'class' => PostTag::class,
                'label' => $this->translator->translate('post_tag.tag')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => PostTag::class
            ]);
    }
}
