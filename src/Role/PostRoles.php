<?php

namespace TwinElements\PostBundle\Role;

use TwinElements\AdminBundle\Role\RoleGroupInterface;

final class PostRoles implements RoleGroupInterface
{
    const ROLE_POST_FULL = 'ROLE_POST_FULL';
    const ROLE_POST_EDIT = 'ROLE_POST_EDIT';
    const ROLE_POST_VIEW = 'ROLE_POST_VIEW';
    const ROLE_POST_OWN = 'ROLE_POST_OWN';

    public static function getRoles(): array
    {
        return [self::ROLE_POST_FULL, self::ROLE_POST_EDIT, self::ROLE_POST_VIEW, self::ROLE_POST_OWN];
    }
}
