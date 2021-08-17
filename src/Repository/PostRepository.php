<?php

namespace TwinElements\PostBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use TwinElements\PostBundle\Entity\Post\Post;
use function Doctrine\ORM\QueryBuilder;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findIndexListItemsByCategory($locale, $category)
    {
        $qb = $this->createQueryBuilder('post');
        $qb
            ->select(['post', 'post_translations'])
            ->leftJoin('post.translations', 'post_translations')
            ->where($qb->expr()->eq('post.parent', $category))
            ->orderBy('post.date', 'desc')
        ;

        return $qb->getQuery();
    }

    /**
     * @param int $categoryId
     * @return \Doctrine\ORM\Query
     */
    public function findPostsByCategoryQuery(int $categoryId, $year = null, $month = null, $locale = null)
    {
        $qb = $this->createQueryBuilder('post');
        $qb->join('post.translations', 'translations')
            ->where('translations.enable = :enable')
            ->setParameter('enable', true)
            ->andWhere('post.parent = :parent')
            ->setParameter('parent', $categoryId)
            ->orderBy('post.date', 'DESC');

        if ($year && $month) {
            $fromDate = \DateTime::createFromFormat('Y-m-d', $year . '-' . $month . '-1');
            $endDate = clone $fromDate;
            $endDate->modify('last day of this month');
            $qb->andWhere(
                $qb->expr()->between('post.date', ':from', ':to')
            )
                ->setParameter(':from', $fromDate->format('Y-m-d'))
                ->setParameter('to', $endDate->format('Y-m-d'));
        }

        if ($locale) {
            $qb
                ->andWhere(
                    $qb->expr()->eq('translations.locale', ':locale')
                )
                ->setParameter('locale', $locale);
        }

        return $qb->getQuery();
    }

    public function findLastPosts(int $limit, $locale = null)
    {
        $qb = $this->createQueryBuilder('post');
        $qb->join('post.translations', 'translations')
            ->where('translations.enable = :enable')
            ->setParameter('enable', true)
            ->orderBy('post.date', 'DESC')
            ->setMaxResults($limit);

        if ($locale) {
            $qb
                ->andWhere(
                    $qb->expr()->eq('translations.locale', ':locale')
                )
                ->setParameter('locale', $locale);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $categoryId
     * @param int $limit
     * @return Post[]
     */
    public function findLastPostsByCategory(int $categoryId, int $limit)
    {
        $qb = $this->createQueryBuilder('post');
        $qb->join('post.translations', 'translations')
            ->where('translations.enable = :enable')
            ->setParameter('enable', true)
            ->andWhere('post.parent = :parent')
            ->setParameter('parent', $categoryId)
            ->orderBy('post.date', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function findForArchiveDataByCategory(?int $categoryId)
    {
        $qb = $this->createQueryBuilder('post');
        $qb->select(['post.id', 'post.date'])
            ->join('post.translations', 'translations')
            ->where('translations.enable = :enable')
            ->setParameter('enable', true)
            ->andWhere('post.parent = :parent')
            ->setParameter('parent', $categoryId)
            ->orderBy('post.date', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
