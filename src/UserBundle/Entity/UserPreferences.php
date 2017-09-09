<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserPreferences
 *
 * @ORM\Table(name="user_preferences")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserPreferencesRepository")
 */
class UserPreferences
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
     * @var int
     *
     * @ORM\Column(name="word_count_goal", type="integer", nullable=true)
     */
    private $wordCountGoal;

    /**
     * @var string
     *
     * @ORM\Column(name="period_goal", type="string", nullable=true)
     */
    private $periodGoal;


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
     * Set wordCountGoal
     *
     * @param integer $wordCountGoal
     *
     * @return UserPreferences
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
     * Set periodGoal
     *
     * @param string $periodGoal
     *
     * @return UserPreferences
     */
    public function setPeriodGoal($periodGoal)
    {
        $this->periodGoal = $periodGoal;

        return $this;
    }

    /**
     * Get periodGoal
     *
     * @return string
     */
    public function getPeriodGoal()
    {
        return $this->periodGoal;
    }
}
