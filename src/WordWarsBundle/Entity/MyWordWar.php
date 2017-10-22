<?php

namespace WordWarsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WordWarsBundle\Entity\WordWar;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
* MyWordWar
*
* @ORM\Table(name="my_word_war")
* @ORM\Entity(repositoryClass="WordWarsBundle\Repository\MyWordWarRepository")
*/
class MyWordWar
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
  * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
  */
  private $user;

  /**
   * @var string
   *
   * @ORM\Column(name="content", type="text")
   * @Assert\NotBlank()
   */
  private $content;

  /**
   * @var int
   *
   * @ORM\Column(name="word_count", type="integer")
   */
  private $wordCount;

  /**
  * @ORM\ManyToOne(targetEntity="WordWarsBundle\Entity\WordWar")
  */
  private $word_war;

  /**
   * @var int
   *
   * @ORM\Column(name="counts_for_nano", type="boolean")
   */
  private $countsForNano = false;

  public function __construct($ww, $user) {
    $this->word_war = $ww;
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
   * Set wordCount
   *
   * @param integer $wordCount
   *
   * @return MyWordWar
   */
  public function setWordCount($wordCount)
  {
    $this->wordCount = $wordCount;

    return $this;
  }

  /**
   * Get wordCount
   *
   * @return int
   */
  public function getWordCount()
  {
    return $this->wordCount;
  }

  /**
   * Set wordWar
   *
   * @param \WordWarsBundle\Entity\WordWar $wordWar
   *
   * @return MyWordWar
   */
  public function setWordWar(\WordWarsBundle\Entity\WordWar $wordWar = null)
  {
    $this->word_war = $wordWar;

    return $this;
  }

  /**
   * Get wordWar
   *
   * @return \WordWarsBundle\Entity\WordWar
   */
  public function getWordWar()
  {
    return $this->word_war;
  }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return MyWordWar
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return MyWordWar
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
     * Set countsForNano
     *
     * @param boolean $countsForNano
     *
     * @return MyWordWar
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
