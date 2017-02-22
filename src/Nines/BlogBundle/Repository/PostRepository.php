<?php

namespace Nines\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Nines\BlogBundle\Entity\PostStatus;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository {

    /**
     * Return a full text query, respecting private comments.
     * 
     * @param string $q
     * @param string $private
     * @return Query
     */
    public function fulltextQuery($q, $private = false) {
        $qb = $this->createQueryBuilder('e');
        $qb->addSelect("MATCH_AGAINST (e.title, e.searchable, :q 'IN BOOLEAN MODE') as HIDDEN score");
        $qb->andWhere("MATCH_AGAINST (e.title, e.searchable, :q 'IN BOOLEAN MODE') > 0");
        if( ! $private) {
            $em = $this->getEntityManager();
            $statuses = $em->getRepository(PostStatus::class)->findBy(array(
                'public' => true,
            ));
            $qb->andWhere('e.status = :status');
            $qb->setParameter('status', $statuses);
        }
        $qb->orderBy('score', 'desc');
        $qb->setParameter('q', $q);
        return $qb->getQuery();
    }
    
    /**
     * Get a query to list recent blog posts.
     * 
     * @param bool $private
     * @return Query
     */
    public function recentQuery($private = false) {
        $em = $this->getEntityManager();
        $qb = $this->createQueryBuilder('e');
        if( ! $private) {
            $statuses = $em->getRepository(PostStatus::class)->findBy(array(
                'public' => true,
            ));
            $qb->andWhere('e.status = :status');
            $qb->setParameter('status', $statuses);
        }
        $qb->orderBy('e.id', 'DESC');
        return $qb->getQuery();
    }
}