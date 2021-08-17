<?php

namespace TwinElements\PostBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use TwinElements\PostBundle\Entity\PostTag\PostTag;

class PostTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostTag::class);
    }

    public function findIndexListItems(string $locale)
    {
        $qb = $this->createQueryBuilder('post_tag');
        $qb
            ->select(['post_tag', 'post_tag_translations'])
            ->leftJoin('post_tag.translations', 'post_tag_translations');

        return $qb->getQuery()->getResult();
    }

    public function findAll()
    {
        $qb = $this->createQueryBuilder('post_tag');
        $qb
            ->select(['post_tag', 'post_tag_translations'])
            ->leftJoin('post_tag.translations', 'post_tag_translations')
        ->orderBy('post_tag_translations.title', 'asc')
        ;

        return $qb->getQuery()->getResult();
    }
}
