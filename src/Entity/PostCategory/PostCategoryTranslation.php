<?php

namespace TwinElements\PostBundle\Entity\PostCategory;

use TwinElements\AdminBundle\Entity\Traits\EnableInterface;
use TwinElements\AdminBundle\Entity\Traits\EnableTrait;
use TwinElements\SeoBundle\Model\SeoInterface;
use TwinElements\AdminBundle\Entity\Traits\TitleInterface;
use TwinElements\AdminBundle\Entity\Traits\TitleTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use TwinElements\SeoBundle\Model\SeoTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post_category_translation")
 */
class PostCategoryTranslation implements TranslationInterface, TitleInterface, EnableInterface, SeoInterface
{
    use TranslationTrait,
        TitleTrait,
        EnableTrait,
        SeoTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
