<?php

namespace TwinElements\PostBundle\Entity\Post;

class SearchPost
{
    /**
     * @var string|null
     */
    private $title;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }
}
