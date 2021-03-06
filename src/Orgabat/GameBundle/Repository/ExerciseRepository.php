<?php

namespace Orgabat\GameBundle\Repository;

use Orgabat\GameBundle\Entity\Category;

/**
 * ExerciseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExerciseRepository extends \Doctrine\ORM\EntityRepository
{
    public function getExercisesOfCategoryWithUserInfos(Category $category, $user)
    {
        return $this->createQueryBuilder('e')
            ->join('e.category', 'cat')
            ->addSelect('cat')
            ->where('e.category = :category')
            ->setParameter('category', $category)
            ->leftJoin('e.exerciseHistories', 'eh', 'WITH', 'eh.user = :user')
            ->setParameter('user', $user)
            ->addSelect('eh')
            ->orderBy('e.id', 'asc')
            ->getQuery()
            ->getResult()
        ;
    }
}
