<?php

namespace TwinElements\PostBundle\Entity\PostTag;

use TwinElements\AdminBundle\Entity\Traits\IdTrait;
use TwinElements\AdminBundle\Entity\Traits\TitleInterface;
use TwinElements\AdminBundle\Entity\Traits\TitleSlugInterface;
use TwinElements\AdminBundle\Entity\Traits\TitleSlugTrait;
use TwinElements\AdminBundle\Entity\Traits\TitleTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post_tag_translation")
 */
class PostTagTranslation implements TranslationInterface, TitleInterface, TitleSlugInterface
{
    use IdTrait,
        TranslationTrait,
        TitleTrait,
        TitleSlugTrait;
}
