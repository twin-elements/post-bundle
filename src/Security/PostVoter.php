<?php

namespace TwinElements\PostBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use TwinElements\AdminBundle\Entity\AdminUser;
use TwinElements\AdminBundle\Role\AdminUserRole;
use TwinElements\PostBundle\Entity\Post\Post;
use TwinElements\PostBundle\Role\PostRoles;

class PostVoter extends Voter
{
    const FULL = 'full';
    const VIEW = 'view';
    const EDIT = 'edit';
    const OWN = 'own';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::FULL, self::VIEW, self::EDIT, self::OWN]) && $subject instanceof Post;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof AdminUser) {
            return false;
        }

        switch ($attribute) {
            case self::FULL;
                return $this->isFull();

            case self::EDIT;
                return $this->canEdit();

            case self::OWN;
                return $this->onlyOwnPosts($subject, $user);

            case self::VIEW;
                return $this->canView($subject, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function isFull()
    {
        if ($this->security->isGranted(AdminUserRole::ROLE_ADMIN)) {
            return true;
        }

        if ($this->security->isGranted(PostRoles::ROLE_POST_FULL)) {
            return true;
        }
    }

    private function canEdit()
    {
        if ($this->isFull()) {
            return true;
        }

        if ($this->security->isGranted(PostRoles::ROLE_POST_EDIT)) {
            return true;
        }
    }

    private function onlyOwnPosts(Post $post, AdminUser $user)
    {
        if (
            $this->security->isGranted(PostRoles::ROLE_POST_OWN)
            &&
            (is_null($post->getCreatedBy()) || $post->getCreatedBy()->getId() === $user->getId())
        ) {
            return true;
        }
    }

    private function canView(Post $post, AdminUser $user)
    {
        if ($this->canEdit()) {
            return true;
        }

        if ($this->onlyOwnPosts($post, $user)) {
            return true;
        }

        if ($this->security->isGranted(PostRoles::ROLE_POST_VIEW)) {
            return true;
        }
    }
}
