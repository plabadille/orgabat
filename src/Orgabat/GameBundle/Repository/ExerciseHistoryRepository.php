<?php

namespace Orgabat\GameBundle\Repository;

/**
 * HistoricalRealisationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExerciseHistoryRepository extends \Doctrine\ORM\EntityRepository
{

    public function getAllHistoryExercicesByUser($user) {
        return $this->createQueryBuilder('eh')
            ->where('eh.user = :user')
            ->setParameter('user', $user)
            ->addOrderBy('eh.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

}
