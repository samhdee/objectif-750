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
    private $wordCountGoal = 750;

    /**
     * @var string
     *
     * @ORM\Column(name="period_goal", type="string")
     */
    private $periodGoal = 'jour';

    /**
     * @var int
     *
     * @ORM\Column(name="nano_mode", type="boolean")
     */
    private $nanoMode = false;

    /**
     * @var int
     *
     * @ORM\Column(name="dark_mode", type="boolean", nullable=true)
     */
    private $darkMode = false;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string")
     */
    private $language = 'fr';


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

    /**
     * Set nanoMode
     *
     * @param boolean $nanoMode
     *
     * @return UserPreferences
     */
    public function setNanoMode($nanoMode)
    {
        $this->nanoMode = $nanoMode;

        return $this;
    }

    /**
     * Get nanoMode
     *
     * @return boolean
     */
    public function getNanoMode()
    {
        return $this->nanoMode;
    }

    /**
     * Set darkMode
     *
     * @param boolean $darkMode
     *
     * @return UserPreferences
     */
    public function setDarkMode($darkMode)
    {
        $this->darkMode = $darkMode;

        return $this;
    }

    /**
     * Get darkMode
     *
     * @return boolean
     */
    public function getDarkMode()
    {
        return $this->darkMode;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return UserPreferences
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
