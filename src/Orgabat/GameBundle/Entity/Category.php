<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Orgabat\GameBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Orgabat\GameBundle\Entity\Exercise", mappedBy="category")
     */
    private $exercises;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->exercises = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add exercise
     *
     * @param \Orgabat\GameBundle\Entity\Exercise $exercise
     *
     * @return Category
     */
    public function addExercise(\Orgabat\GameBundle\Entity\Exercise $exercise)
    {
        $this->exercises[] = $exercise;

        return $this;
    }

    /**
     * Remove exercise
     *
     * @param \Orgabat\GameBundle\Entity\Exercise $exercise
     */
    public function removeExercise(\Orgabat\GameBundle\Entity\Exercise $exercise)
    {
        $this->exercises->removeElement($exercise);
    }


    /**
     * Get exercises
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExercises()
    {
        return $this->exercises;
    }

    public function getDataFromAllBestExercises() {
        $results = ["timer" => 0, "healthNote" => 0, "organizationNote" => 0, "businessNotorietyNote" => 0];
        $compteur = 0;
        foreach ($this->exercises as $exercise) {
            $results["timer"] += $exercise->getBestExerciseHistory()->getTimer();
            $results["healthNote"] += $exercise->getBestExerciseHistory()->getHealthNote();
            $results["organizationNote"] += $exercise->getBestExerciseHistory()->getOrganizationNote();
            $results["businessNotorietyNote"] += $exercise->getBestExerciseHistory()->getBusinessNotorietyNote();
            $compteur ++;
        }
        $results["timer"] /= $compteur;
        return $results;
    }
}
