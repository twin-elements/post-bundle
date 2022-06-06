<?php

namespace TwinElements\PostBundle\Entity\Post;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TwinElements\PostBundle\Repository\PostRepository")
 * @ORM\Table(name="post")
 */
class Post extends BasePost
{
}
