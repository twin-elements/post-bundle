<?php

namespace TwinElements\PostBundle;

use TwinElements\AdminBundle\Menu\AdminMenuInterface;
use TwinElements\AdminBundle\Menu\MenuItem;
use TwinElements\PostBundle\Role\PostRoles;

class AdminMenu implements AdminMenuInterface
{
    public function getItems()
    {
        return [
            MenuItem::newInstance('post_category.post_categories', 'post_category_index', [], 3, PostRoles::ROLE_POST_VIEW)
        ];
    }
}
