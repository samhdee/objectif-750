<?php

namespace MyStatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * MyNanos
 *
 * @ORM\Table(name="my_nanos")
 * @ORM\Entity(repositoryClass="MyStatsBundle\Repository\MyNanosRepository")
 */
class MyNanos
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="word_count_goal", type="integer")
     */
    private $wordCountGoal;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;


    public function __construct($user) {
        $this->user = $user;
        $this->date = new \DateTime();
        $this->wordCountGoal = 50000;
    }

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return MyNanos
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set wordCountGoal
     *
     * @param integer $wordCountGoal
     *
     * @return MyNanos
     */
    public function setWordCountGoal($wordCountGoal)
    {
        $this->wordCountGoal = $wordCountGoal;

        return $this;
    }

    /**
     * Get wordCountGoal
     *
     * @return int
     */
    public function getWordCountGoal()
    {
        return $this->wordCountGoal;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return MyNanos
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
