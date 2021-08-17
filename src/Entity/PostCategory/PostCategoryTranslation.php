<?php

namespace TwinElements\PostBundle\Entity\PostCategory;

use App\Model\EnableInterface;
use App\Model\EnableTrait;
use App\Model\SeoInterface;
use App\Model\SeoTrait;
use App\Model\TitleInterface;
use App\Model\TitleTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

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
