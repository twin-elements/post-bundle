<?php

namespace TwinElements\PostBundle\Entity\Post;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\BlameableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use TwinElements\AdminBundle\Entity\Traits\AttachmentsInterface;
use TwinElements\AdminBundle\Entity\Traits\AttachmentsTrait;
use TwinElements\AdminBundle\Entity\Traits\ContentInterface;
use TwinElements\AdminBundle\Entity\Traits\ContentTrait;
use TwinElements\AdminBundle\Entity\Traits\EnableInterface;
use TwinElements\AdminBundle\Entity\Traits\EnableTrait;
use TwinElements\AdminBundle\Entity\Traits\IdTrait;
use TwinElements\AdminBundle\Entity\Traits\ImageAlbumInterface;
use TwinElements\AdminBundle\Entity\Traits\ImageAlbumTrait;
use TwinElements\AdminBundle\Entity\Traits\ImageInterface;
use TwinElements\AdminBundle\Entity\Traits\ImageTrait;
use TwinElements\AdminBundle\Entity\Traits\TitleInterface;
use TwinElements\AdminBundle\Entity\Traits\TitleSlugInterface;
use TwinElements\AdminBundle\Entity\Traits\TitleSlugTrait;
use TwinElements\AdminBundle\Entity\Traits\TitleTrait;
use TwinElements\SeoBundle\Model\SeoInterface;
use TwinElements\SeoBundle\Model\SeoTrait;

/**
 * @ORM\MappedSuperclass()
 */
abstract class BasePostTranslation implements
    TranslationInterface,
    BlameableInterface,
    TimestampableInterface,
    TitleInterface,
    TitleSlugInterface,
    EnableInterface,
    ImageAlbumInterface,
    AttachmentsInterface,
    SeoInterface,
    ImageInterface,
    ContentInterface
{
    use IdTrait,
        TranslationTrait,
        TimestampableTrait,
        BlameableTrait,
        TitleTrait,
        TitleSlugTrait,
        EnableTrait,
        ImageAlbumTrait,
        AttachmentsTrait,
        SeoTrait,
        ImageTrait,
        ContentTrait;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected $teaser;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $thumbnail;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
