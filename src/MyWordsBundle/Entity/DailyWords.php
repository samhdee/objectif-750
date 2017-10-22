<?php

namespace MyWordsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * DailyWords
 *
 * @ORM\Table(name="daily_words")
 * @ORM\Entity(repositoryClass="MyWordsBundle\Repository\DailyWordsRepository")
 */
class DailyWords
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
   * @ORM\Column(name="content", type="text")
   */
  private $content;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="date", type="datetime")
   */
  private $date;

  /**
  * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
  */
  private $user;

  /**
   * @var int
   *
   * @ORM\Column(name="word_count", type="integer")
   */
  private $wordCount;

  /**
   * @var int
   *
   * @ORM\Column(name="todays_goal", type="integer")
   */
  private $todaysGoal;

  /**
   * @var int
   *
   * @ORM\Column(name="counts_for_nano", type="boolean")
   */
  private $countsForNano = false;


  public function __construct($user) {
    $this->user = $user;
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
   * Set content
   *
   * @param string $content
   *
   * @return DailyWords
   */
  public function setContent($content)
  {
    $this->content = $content;

    return $this;
  }

  /**
   * Get content
   *
   * @return string
   */
  public function getContent()
  {
    return $this->content;
  }

  /**
   * Set date
   *
   * @param \DateTime $date
   *
   * @return DailyWords
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
   * Set user
   *
   * @param \UserBundle\Entity\User $user
   *
   * @return DailyWords
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

    /**
     * Set wordCount
     *
     * @param integer $wordCount
     *
     * @return DailyWords
     */
    public function setWordCount($wordCount)
    {
        $this->wordCount = $wordCount;

        return $this;
    }

    /**
     * Get wordCount
     *
     * @return integer
     */
    public function getWordCount()
    {
        return $this->wordCount;
    }

    /**
     * Set todaysGoal
     *
     * @param integer $todaysGoal
     *
     * @return DailyWords
     */
    public function setTodaysGoal($todaysGoal)
    {
        $this->todaysGoal = $todaysGoal;

        return $this;
    }

    /**
     * Get todaysGoal
     *
     * @return integer
     */
    public function getTodaysGoal()
    {
        return $this->todaysGoal;
    }

    /**
     * Set countsForNano
     *
     * @param boolean $countsForNano
     *
     * @return DailyWords
     */
    public function setCountsForNano($countsForNano)
    {
        $this->countsForNano = $countsForNano;

        return $this;
    }

    /**
     * Get countsForNano
     *
     * @return boolean
     */
    public function getCountsForNano()
    {
        return $this->countsForNano;
    }
}
