<?php

namespace Orgabat\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PUGX\MultiUserBundle\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="apprentice")
 * @UniqueEntity(fields = "username", targetClass = "Orgabat\GameBundle\Entity\User", message="fos_user.username.already_used")
 */
class Apprentice extends User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Orgabat\GameBundle\Entity\Section", inversedBy="apprentices", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $section;

    /**
     * Format: JJMMAAAA
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *      pattern="/^(((0[1-9]|[12][0-9]|30)?(0[13-9]|1[012])|31?(0[13578]|1[02])|(0[1-9]|1[0-9]|2[0-8])?02)?[0-9]{4}|29?02?([0-9]{2}(([2468][048]|[02468][48])|[13579][26])|([13579][26]|[02468][048]|0[0-9]|1[0-6])00))$/",
     *      message="Le champ doit être de la forme JJMMAAAA"
     * )
     */
    private $birthDate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    public function getFormattedBirthDate() {
        $day = substr($this->birthDate,0,2);
        $month = substr($this->birthDate,2,2);
        $year = substr($this->birthDate,4,4);

        $fullDate = $year . '-' . $month . '-' . $day . " 00:00:00";
        return new \DateTime($fullDate);
    }

    /**
     * @param mixed $section
     */
    public function setSection($section)
    {
        $this->section = $section;
        $section->addApprentice($this);
    }

    /**
     * Returns an array of data for the csv generation
     * @return array Apprentice data
     */
    public function getData()
    {
        return [
            $this->getFirstName(),
            $this->getLastName(),
            $this->getBirthDate(),
            $this->getEmail(),
            ($this->getSection() !== null)
            ? $this->getSection()->getName()
            : "null"
        ];
    }

}
