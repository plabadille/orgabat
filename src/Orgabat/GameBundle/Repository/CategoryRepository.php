<?php

namespace Orgabat\GameBundle\Repository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Orgabat\GameBundle\Entity\Apprentice;

/**
 * CategoryRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{   
    /**
    * Important note : because of some unknow error (maybe context issue) this doesn't work for multiple user, please use the array version below.
    *
    */
    public function getExercisesOfAllCategoriesByUser($apprentice)
    {
        return $this->createQueryBuilder('c')
            ->join('c.exercises', 'e')
            ->addSelect('e')
            ->join('e.exerciseHistories', 'eh', 'WITH', 'eh.user = :user')
            ->setParameter('user', $apprentice)
            ->addSelect('eh')
            ->addOrderBy('c.id', 'ASC')
            ->addOrderBy('e.id', 'ASC')
            ->addOrderBy('eh.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @ParamConverter("apprentice", options={"mapping": {"apprentice_id": "id"}})
    */
    public function getExercisesOfAllCategoriesByUserArray(Apprentice $apprentice)
    {
        return $this->createQueryBuilder('c')
            ->join('c.exercises', 'e')
            ->addSelect('e')
            ->join('e.exerciseHistories', 'eh', 'WITH', 'eh.user = :user')
            ->setParameter('user', $apprentice)
            ->addSelect('eh')
            ->addOrderBy('c.id', 'ASC')
            ->addOrderBy('e.id', 'ASC')
            ->addOrderBy('eh.date', 'DESC')
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
