<?php

namespace TwinElements\PostBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use TwinElements\PostBundle\Entity\PostCategory\PostCategory;

class PostCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostCategory::class);
    }

    public function findIndexListItems(string $locale)
    {
        $qb = $this->createQueryBuilder('post_category');
        $qb
            ->select(['post_category', 'post_category_translations'])
            ->leftJoin('post_category.translations', 'post_category_translations');

        return $qb->getQuery()->getResult();
    }

    public function findItemsForUrlBuilder(string $locale)
    {
        $qb = $this->createQueryBuilder('post_category');
        $qb
            ->select(['post_category', 'post_category_translations'])
            ->leftJoin('post_category.translations', 'post_category_translations')
            ->where(
                $qb->expr()->eq('post_category_translations.locale', ':locale')
            )
            ->setParameter('locale', $locale);

        return $qb->getQuery()->getResult();
    }
}
