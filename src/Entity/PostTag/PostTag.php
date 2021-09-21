<?php

namespace TwinElements\PostBundle\Entity\PostTag;

use TwinElements\AdminBundle\Entity\Traits\IdTrait;
use TwinElements\AdminBundle\Entity\Traits\TranslatableTitle;
use TwinElements\AdminBundle\Entity\Traits\TranslatableTitleSlug;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\BlameableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="TwinElements\PostBundle\Repository\PostTagRepository")
 * @ORM\Table(name="post_tag")
 */
class PostTag implements TranslatableInterface, BlameableInterface, TimestampableInterface
{
    use IdTrait,
        BlameableTrait,
        TimestampableTrait,
        TranslatableTrait,
        TranslatableTitleSlug,
        TranslatableTitle;

    public function __toString(): string
    {
        return $this->translate(null, false)->getTitle();
    }
}
