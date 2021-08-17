<?php

namespace TwinElements\PostBundle\Entity\PostTag;

use App\Model\IdTrait;
use App\Model\TitleInterface;
use App\Model\TitleSlugInterface;
use App\Model\TitleSlugTrait;
use App\Model\TitleTrait;
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
