<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Exercise.
 *
 * @ORM\Table(name="exercise")
 * @ORM\Entity(repositoryClass="Orgabat\GameBundle\Repository\ExerciseRepository")
 */
class Exercise
{
    /**
     * @var float Value of the minimum note to consider the exercise done
     */
    const MINIMUM_NOTE = 0.65;

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
     * @ORM\ManyToOne(targetEntity="Orgabat\GameBundle\Entity\Category", inversedBy="exercises", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var int
     *
     * @ORM\Column(name="healthMaxNote", type="integer")
     * @Assert\Range(
     *     min = 0,
     *     minMessage= "La note de peut pas être inférieure à {{ limit }}."
     * )
     */
    private $healthMaxNote;

    /**
     * @var int
     *
     * @ORM\Column(name="organizationMaxNote", type="integer")
     * @Assert\Range(
     *     min = 0,
     *     minMessage= "La note de peut pas être inférieure à {{ limit }}."
     * )
     */
    private $organizationMaxNote;

    /**
     * @var int
     *
     * @ORM\Column(name="businessNotorietyMaxNote", type="integer")
     * @Assert\Range(
     *     min = 0,
     *     minMessage= "La note de peut pas être inférieure à {{ limit }}."
     * )
     */
    private $businessNotorietyMaxNote;

    /**
     * @ORM\OneToMany(targetEntity="Orgabat\GameBundle\Entity\ExerciseHistory", mappedBy="exercise")
     */
    private $exerciseHistories;

    /**
     * @var int
     *
     * @ORM\Column(name="code", type="integer")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, unique=false)
     */
    private $description;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get code.
     *
     * @return int
     */
    public function getCode() 
    {
        return $this->code;
    }

    /**
     * Set code.
     *
     * @param int $code
     *
     * @return Exercise
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Exercise
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Exercise
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category.
     *
     * @param \Orgabat\GameBundle\Entity\Category $category
     *
     * @return Exercise
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        $category->addExercise($this);

        return $this;
    }

    /**
     * Get category.
     *
     * @return \Orgabat\GameBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set healthMaxNote.
     *
     * @param int $healthMaxNote
     *
     * @return Exercise
     */
    public function setHealthMaxNote($healthMaxNote)
    {
        $this->healthMaxNote = $healthMaxNote;

        return $this;
    }

    /**
     * Get healthMaxNote.
     *
     * @return int
     */
    public function getHealthMaxNote()
    {
        return $this->healthMaxNote;
    }

    /**
     * Set organizationMaxNote.
     *
     * @param int $organizationMaxNote
     *
     * @return Exercise
     */
    public function setOrganizationMaxNote($organizationMaxNote)
    {
        $this->organizationMaxNote = $organizationMaxNote;

        return $this;
    }

    /**
     * Get organizationMaxNote.
     *
     * @return int
     */
    public function getOrganizationMaxNote()
    {
        return $this->organizationMaxNote;
    }

    /**
     * Set businessNotorietyMaxNote.
     *
     * @param int $businessNotorietyMaxNote
     *
     * @return Exercise
     */
    public function setBusinessNotorietyMaxNote($businessNotorietyMaxNote)
    {
        $this->businessNotorietyMaxNote = $businessNotorietyMaxNote;

        return $this;
    }

    /**
     * Get businessNotorietyMaxNote.
     *
     * @return int
     */
    public function getBusinessNotorietyMaxNote()
    {
        return $this->businessNotorietyMaxNote;
    }
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->exerciseHistories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add exerciseHistory.
     *
     * @param \Orgabat\GameBundle\Entity\ExerciseHistory $exerciseHistory
     *
     * @return Exercise
     */
    public function addExerciseHistory(\Orgabat\GameBundle\Entity\ExerciseHistory $exerciseHistory)
    {
        $this->exerciseHistories[] = $exerciseHistory;

        return $this;
    }

    /**
     * Remove exerciseHistory.
     *
     * @param \Orgabat\GameBundle\Entity\ExerciseHistory $exerciseHistory
     */
    public function removeExerciseHistory(\Orgabat\GameBundle\Entity\ExerciseHistory $exerciseHistory)
    {
        $this->exerciseHistories->removeElement($exerciseHistory);
    }

    /**
     * Get exerciseHistories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExerciseHistories()
    {
        return $this->exerciseHistories;
    }

    /**
     * Get bestExerciseHistory.
     *
     * @return \Orgabat\GameBundle\Entity\ExerciseHistory
     */
    public function getBestExerciseHistory()
    {
        if (count($this->exerciseHistories) == 0) {
            return null;
        }

        $tab = ['index' => -1, 'score' => -1];
        foreach ($this->exerciseHistories as $index => $try) {
            if ($try->getScore() > $tab['score']) {
                $tab['score'] = $try->getScore();
                $tab['index'] = $index;
            }
        }
        return $this->exerciseHistories[$tab['index']];
    }

    /**
     * Get the max score.
     *
     * @return int The max score
     */
    public function getMaxScore()
    {
        return $this->getHealthMaxNote()
            + $this->getOrganizationMaxNote()
            + $this->getBusinessNotorietyMaxNote();
    }

    /**
     * Get the min score.
     *
     * @return float The min score
     */
    public function getMinScore()
    {
        return ceil($this->getMaxScore() * self::MINIMUM_NOTE);
    }

    /**
     * Check if the game has a try above the minimum note.
     *
     * @return bool
     */
    public function isFinished()
    {
        $best = $this->getBestExerciseHistory();
        if (is_null($best)) {
            return false;
        }

        return $best->getScore() >= self::MINIMUM_NOTE;
    }
}
