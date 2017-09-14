<?php

namespace WordWarsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * WordWar
 *
 * @ORM\Table(name="word_war")
 * @ORM\Entity(repositoryClass="WordWarsBundle\Repository\WordWarRepository")
 */
class WordWar
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
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(name="start", type="datetime")
     * @Assert\DateTime()
     */
    private $start;

    /**
     * @ORM\Column(name="end", type="datetime")
     * @Assert\DateTime()
     */
    private $end;

    /**
    * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
    */
    private $author;

    public function __construct($user) {
      $this->author = $user;
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
     * Set start
     *
     * @param \DateTime $start
     *
     * @return WordWar
     */
    public function setStart($start)
    {
      $this->start = $start;

      return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
      return $this->start;
    }

    /**
     * Set author
     *
     * @param \UserBundle\Entity\User $author
     *
     * @return WordWar
     */
    public function setAuthor(\UserBundle\Entity\User $author = null)
    {
      $this->author = $author;

      return $this;
    }

    /**
     * Get author
     *
     * @return \UserBundle\Entity\User
     */
    public function getAuthor()
    {
      return $this->author;
    }

    /**
     * Set end
     *
     * @param integer $end
     *
     * @return WordWar
     */
    public function setEnd($end)
    {
      $this->end = $end;

      return $this;
    }

    /**
     * Get end
     *
     * @return integer
     */
    public function getEnd()
    {
      return $this->end;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return WordWar
     */
    public function setTitle($title)
    {
      $this->title = $title;

      return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
      return $this->title;
    }

    /**
     * @Assert\Callback
     */
    public function isWWValid(ExecutionContextInterface $context) {
      $now = new \DateTime();
      $start = new \DateTime($this->getStart()->format('H:i:s'));
      $end = new \DateTime($this->getEnd()->format('H:i:s'));

      if($start < $now) {
        $context
          ->buildViolation('La WW ne peut pas démarrer dans le passé !')
          ->atPath('start')
          ->addViolation();
      }

      if($start > $end) {
        $context
          ->buildViolation('La WW doit démarrer avant de finir !')
          ->atPath('end')
          ->addViolation();
      }

      if($start == $end) {
        $context
          ->buildViolation("L'heure de fin doit être différente de celle de début !")
          ->atPath('end')
          ->addViolation();
      }
    }
  }
