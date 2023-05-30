<?php

namespace TwinElements\PostBundle\UrlGenerator;

use TwinElements\PostBundle\Entity\Post\Post;

interface PostPreviewGeneratorInterface
{
    public function generatePreviewUrl(Post $post): string;
}