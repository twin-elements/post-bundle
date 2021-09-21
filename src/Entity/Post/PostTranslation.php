<?php

namespace TwinElements\PostBundle\Entity\Post;

use TwinElements\AdminBundle\Entity\Traits\AttachmentsInterface;
use TwinElements\AdminBundle\Entity\Traits\AttachmentsTrait;
use TwinElements\AdminBundle\Entity\Traits\EnableInterface;
use TwinElements\AdminBundle\Entity\Traits\EnableTrait;
use TwinElements\AdminBundle\Entity\Traits\ImageAlbumInterface;
use TwinElements\AdminBundle\Entity\Traits\ImageAlbumTrait;
use TwinElements\AdminBundle\Entity\Traits\ImageInterface;
use TwinElements\AdminBundle\Entity\Traits\ImageTrait;
use TwinElements\SeoBundle\Model\SeoInterface;
use TwinElements\SeoBundle\Model\SeoTrait;
use TwinElements\AdminBundle\Entity\Traits\TitleTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Contract\Entity\BlameableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post_translation")
 */
class PostTranslation implements
    TranslationInterface,
    BlameableInterface,
    TimestampableInterface,
    EnableInterface,
    ImageAlbumInterface,
    AttachmentsInterface,
    SeoInterface,
    ImageInterface
{
    use TranslationTrait,
        TimestampableTrait,
        BlameableTrait,
        EnableTrait,
        ImageAlbumTrait,
        AttachmentsTrait,
        TitleTrait,
        SeoTrait,
        ImageTrait;

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
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $teaser;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

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

    /**
     * @return string|null
     */
    public function getTeaser(): ?string
    {
        return $this->teaser;
    }

    /**
     * @param string|null $teaser
     */
    public function setTeaser(?string $teaser): void
    {
        $this->teaser = $teaser;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string|null
     */
    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    /**
     * @param string|null $thumbnail
     */
    public function setThumbnail(?string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }
}
