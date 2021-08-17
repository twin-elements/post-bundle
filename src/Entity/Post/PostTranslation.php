<?php

namespace TwinElements\PostBundle\Entity\Post;

use App\Model\AttachmentsInterface;
use App\Model\AttachmentsTrait;
use App\Model\EnableInterface;
use App\Model\EnableTrait;
use App\Model\ImageAlbumInterface;
use App\Model\ImageAlbumTrait;
use App\Model\SeoInterface;
use App\Model\SeoTrait;
use App\Model\TitleTrait;
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
    SeoInterface
{
    use TranslationTrait,
        TimestampableTrait,
        BlameableTrait,
        EnableTrait,
        ImageAlbumTrait,
        AttachmentsTrait,
        TitleTrait,
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
    private $cover;

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
    public function getCover(): ?string
    {
        return $this->cover;
    }

    /**
     * @param string|null $cover
     */
    public function setCover(?string $cover): void
    {
        $this->cover = $cover;
    }
}
