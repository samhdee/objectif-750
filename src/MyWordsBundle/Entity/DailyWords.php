<?php

namespace MyWordsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use WordWarsBundle\Entity\WordWar;

/**
 * DailyWords
 *
 * @ORM\Table(name="daily_words")
 * @ORM\Entity(repositoryClass="MyWordsBundle\Repository\DailyWordsRepository")
 */
class DailyWords
{
  const NUM_ENTRIES = 10;

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
   * @var string
   *
   * @ORM\Column(name="type", type="string", length=255)
   */
  private $type = 'solo';

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
  * @ORM\ManyToOne(targetEntity="WordWarsBundle\Entity\WordWar")
  * @ORM\JoinColumn(nullable=true)
  */
  private $wordWar;


  public function __construct($user, $type = 'solo', $word_war = null) {
    $this->user = $user;
    $this->content = '';
    $this->wordCount = 0;
    $this->type = $type;
    $this->date = new \DateTime();

    if('word_war' === $type) {
      $this->wordWar = $word_war;
    }
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
     * Set type
     *
     * @param string $type
     *
     * @return DailyWords
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set wordWar
     *
     * @param \WordWarsBundle\Entity\WordWar $wordWar
     *
     * @return DailyWords
     */
    public function setWordWar(\WordWarsBundle\Entity\WordWar $wordWar = null)
    {
        $this->wordWar = $wordWar;

        return $this;
    }

    /**
     * Get wordWar
     *
     * @return \WordWarsBundle\Entity\WordWar
     */
    public function getWordWar()
    {
        return $this->wordWar;
    }
}
