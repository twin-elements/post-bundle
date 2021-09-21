<?php

namespace TwinElements\PostBundle\Form;

use TwinElements\SeoBundle\Form\Admin\SeoType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwinElements\AdminBundle\Service\AdminTranslator;
use TwinElements\FormExtensions\Type\AttachmentsType;
use TwinElements\FormExtensions\Type\DatePickerType;
use TwinElements\FormExtensions\Type\SaveButtonsType;
use TwinElements\FormExtensions\Type\TECollectionType;
use TwinElements\FormExtensions\Type\TEEntityType;
use TwinElements\FormExtensions\Type\TEUploadType;
use TwinElements\FormExtensions\Type\TinymceType;
use TwinElements\FormExtensions\Type\ToggleChoiceType;
use TwinElements\PostBundle\Entity\Post\Post;
use TwinElements\PostBundle\Entity\PostCategory\PostCategory;
use TwinElements\PostBundle\Entity\PostTag\PostTag;

class PostType extends AbstractType
{
    /**
     * @var AdminTranslator $translator
     */
    private $translator;
    /**
     * @var array|null
     */
    private $coverSize;

    public function __construct(AdminTranslator $translator, ParameterBagInterface $parameterBag)
    {
        $this->translator = $translator;
        if (!$parameterBag->has('post')) {
            throw new \Exception('No available post parameter in services.yaml');
        }

        $this->coverSize = $parameterBag->get('post')['cover_size'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->translate('post.title')
            ])
            ->add('date', DatePickerType::class, [
                'label' => $this->translator->translate('post.date')
            ])
            ->add('image', TEUploadType::class, [
                'label' => $this->translator->translate('post.image'),
                'required' => false
            ])
            ->add('thumbnail', TEUploadType::class, [
                'label' => $this->translator->translate('post.thumbnail'),
                'help' => $this->translator->translate('post.image_help', ['%width%' => $this->coverSize[0], '%height%' => $this->coverSize[1]]),
                'required' => false
            ])
            ->add('teaser', TextareaType::class, [
                'label' => $this->translator->translate('post.teaser'),
                'required' => false
            ])
            ->add('content', TinymceType::class, [
                'label' => $this->translator->translate('post.content'),
                'required' => false
            ])
            ->add('tags', TEEntityType::class, [
                'class' => PostTag::class,
                'multiple' => true,
                'label' => $this->translator->translate('post_tag.post_tags_list'),
                'required' => false
            ])
            ->add('imageAlbum', TECollectionType::class, [
                'entry_type' => TEUploadType::class,
                'entry_options' => [
                    'file_type' => 'image',
                    'label' => $this->translator->translate('admin_type.image_album.choose_image')
                ],
                'min' => 0,
                'label' => $this->translator->translate('admin_type.image_album.image_album')
            ])
            ->add('attachments', AttachmentsType::class)
            ->add('seo', SeoType::class)
            ->add('parent', TEEntityType::class, [
                'class' => PostCategory::class
            ])
            ->add('enable', ToggleChoiceType::class)
            ->add('buttons', SaveButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
