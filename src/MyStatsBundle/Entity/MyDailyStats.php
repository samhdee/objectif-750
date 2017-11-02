<?php

namespace MyStatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
* MyDailyStats
*
* @ORM\Table(name="my_daily_stats")
* @ORM\Entity(repositoryClass="MyStatsBundle\Repository\MyDailyStatsRepository")
*/
class MyDailyStats
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
   * @ORM\Column(name="date", type="datetime")
   */
  private $date;

  /**
   * @var int
   *
   * @ORM\Column(name="my_words_word_count", type="integer")
   */
  private $myWordsWordCount = 0;

  /**
   * @var int
   *
   * @ORM\Column(name="word_wars_word_count", type="integer")
   */
  private $wordWarsWordCount = 0;

  /**
   * @var int
   *
   * @ORM\Column(name="days_goal", type="integer")
   */
  private $daysGoal;

  /**
  * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
  */
  private $user;


  public function __construct($user) {
    $this->user = $user;
    $this->date = new \Datetime();
    $this->myWordsWordCount = 0;
    $this->wordWarsWordCount = 0;
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
   * @return MyDailyStats
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
   * Set daysGoal
   *
   * @param integer $daysGoal
   *
   * @return MyDailyStats
   */
  public function setDaysGoal($daysGoal)
  {
    $this->daysGoal = $daysGoal;

    return $this;
  }

  /**
   * Get daysGoal
   *
   * @return int
   */
  public function getDaysGoal()
  {
    return $this->daysGoal;
  }

  /**
   * Set user
   *
   * @param \UserBundle\Entity\User $user
   *
   * @return MyDailyStats
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
   * Set myWordsWordCount
   *
   * @param integer $myWordsWordCount
   *
   * @return MyDailyStats
   */
  public function setMyWordsWordCount($myWordsWordCount)
  {
      $this->myWordsWordCount = $myWordsWordCount;

      return $this;
  }

  /**
   * Get myWordsWordCount
   *
   * @return integer
   */
  public function getMyWordsWordCount()
  {
      return $this->myWordsWordCount;
  }

  /**
   * Set wordWarsWordCount
   *
   * @param integer $wordWarsWordCount
   *
   * @return MyDailyStats
   */
  public function setWordWarsWordCount($wordWarsWordCount)
  {
      $this->wordWarsWordCount = $wordWarsWordCount;

      return $this;
  }

  /**
   * Get wordWarsWordCount
   *
   * @return integer
   */
  public function getWordWarsWordCount()
  {
      return $this->wordWarsWordCount;
  }
}
