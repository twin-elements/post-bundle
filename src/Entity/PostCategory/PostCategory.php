<?php


namespace TwinElements\PostBundle\Entity\PostCategory;


use App\Model\SeoInterface;
use App\Model\SeoTrait;
use App\Model\SeoTranslatableTrait;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\BlameableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Blameable\BlameableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="TwinElements\PostBundle\Repository\PostCategoryRepository")
 * @ORM\Table(name="post_category")
 */
class PostCategory implements TranslatableInterface, BlameableInterface, TimestampableInterface, SeoInterface
{
    use TranslatableTrait,
        BlameableTrait,
        TimestampableTrait,
        SeoTranslatableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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

    public function getTitle()
    {
        return $this->translate(null, false)->getTitle();
    }

    public function setTitle($title)
    {
        $this->translate(null, false)->setTitle($title);
    }

    public function getSlug()
    {
        return $this->translate(null, false)->getSlug();
    }
}
