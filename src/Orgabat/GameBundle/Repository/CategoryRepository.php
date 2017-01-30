<?php

namespace Orgabat\GameBundle\Repository;

/**
 * CategoryRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function getExercisesOfAllCategoriesByUser($user)
    {
        return $this->createQueryBuilder('c')
            ->join('c.exercises', 'e')
            ->addSelect('e')
            ->join('e.exerciseHistories', 'eh', 'WITH', 'eh.user = :user')
            ->setParameter('user', $user)
            ->addSelect('eh')
            ->addOrderBy('c.id', 'ASC')
            ->addOrderBy('e.id', 'ASC')
            ->addOrderBy('eh.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
