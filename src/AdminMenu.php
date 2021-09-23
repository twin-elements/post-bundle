<?php

namespace TwinElements\PostBundle;

use TwinElements\AdminBundle\Menu\AdminMenuInterface;
use TwinElements\AdminBundle\Menu\MenuItem;
use TwinElements\PostBundle\Entity\Post\Post;
use TwinElements\PostBundle\Security\PostVoter;

class AdminMenu implements AdminMenuInterface
{
    public function getItems()
    {
        return [
            MenuItem::newInstance('post_category.post_categories', 'post_category_index', [], 3, null, [PostVoter::VIEW, new Post()])
        ];
    }
}
