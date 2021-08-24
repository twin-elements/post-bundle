<?php

namespace TwinElements\PostBundle\Entity\Post;

use App\Model\SeoInterface;
use App\Model\SeoTranslatableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\LoggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Loggable\LoggableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use TwinElements\PostBundle\Entity\PostCategory\PostCategory;
use TwinElements\PostBundle\Entity\PostTag\PostTag;

/**
 * @ORM\Entity(repositoryClass="TwinElements\PostBundle\Repository\PostRepository")
 * @ORM\Table(name="post")
 */
class Post implements TranslatableInterface, LoggableInterface, SeoInterface
{
    use TranslatableTrait,
        LoggableTrait,
        SeoTranslatableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var PostCategory|null
     * @ORM\ManyToOne(targetEntity="TwinElements\PostBundle\Entity\PostCategory\PostCategory")
     */
    private $parent;

    /**
     * @ORM\ManyToMany(targetEntity="TwinElements\PostBundle\Entity\PostTag\PostTag", fetch="EXTRA_LAZY")
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        if (is_null($this->translate(null, false)->getTitle())) {
            return 'no translation';
        } else {
            return $this->translate(null, false)->getTitle();
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PostCategory|null
     */
    public function getParent(): ?PostCategory
    {
        return $this->parent;
    }

    /**
     * @param PostCategory|null $parent
     */
    public function setParent(?PostCategory $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     */
    public function setDate(?\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getTitle()
    {
        return $this->translate(null, false)->getTitle();
    }

    public function setTitle($title)
    {
        $this->translate(null, false)->setTitle($title);
    }

    public function getTeaser()
    {
        return $this->translate(null, false)->getTeaser();
    }

    public function setTeaser($teaser)
    {
        $this->translate(null, false)->setTeaser($teaser);
    }

    public function getContent()
    {
        return $this->translate(null, false)->getContent();
    }

    public function setContent($content)
    {
        $this->translate(null, false)->setContent($content);
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->translate(null, false)->isEnable();
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->translate(null, false)->setEnable($enable);
    }

    public function getImage()
    {
        return $this->translate(null, false)->getImage();
    }

    public function setImage($image)
    {
        $this->translate(null, false)->setImage($image);
    }

    /**
     * @deprecated use getImage() instead
     */
    public function getCover()
    {
        return $this->translate(null, false)->getImage();
    }

    /**
     * @deprecated use setImage() instead
     */
    public function setCover($image)
    {
        $this->translate(null, false)->setImage($image);
    }

    public function getThumbnail()
    {
        return $this->translate(null, false)->getThumbnail();
    }

    public function setThumbnail($thumbnail)
    {
        $this->translate(null, false)->setThumbnail($thumbnail);
    }

    public function getSlug()
    {
        return $this->translate(null, false)->getSlug();
    }

    public function getCreatedAt()
    {
        return $this->translate(null, false)->getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return $this->translate(null, false)->getUpdatedAt();
    }

    public function getCreatedBy()
    {
        return $this->translate(null, false)->getCreatedBy();
    }

    public function getUpdatedBy()
    {
        return $this->translate(null, false)->getUpdatedBy();
    }

    public function getImageAlbum()
    {
        return $this->translate(null, false)->getImageAlbum();
    }

    public function setImageAlbum($imageAlbum)
    {
        return $this->translate(null, false)->setImageAlbum($imageAlbum);
    }

    public function getAttachments()
    {
        return $this->translate(null, false)->getAttachments();
    }

    public function setAttachments($attachments)
    {
        return $this->translate(null, false)->setAttachments($attachments);
    }

    /**
     * @return Collection|PostTag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(PostTag $postTag): self
    {
        if (!$this->tags->contains($postTag)) {
            $this->tags[] = $postTag;
            $postTag->addPost($this);
        }

        return $this;
    }

    public function removeTag(PostTag $postTag)
    {
        $this->tags->removeElement($postTag);

        return $this;
    }
}
