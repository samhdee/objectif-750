<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\UserPreferences", cascade={"persist"})
     */
    private $user_preferences;

    /**
    * @ORM\OneToOne(targetEntity="UserBundle\Entity\Avatar", cascade={"persist", "remove"})
    */
    private $avatar;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
      return $this->id;
    }

    public function eraseCredentials()
    {

    }

    /**
     * Set userPreferences
     *
     * @param \UserBundle\Entity\UserPreferences $userPreferences
     *
     * @return User
     */
    public function setUserPreferences(\UserBundle\Entity\UserPreferences $userPreferences = null)
    {
        $this->user_preferences = $userPreferences;

        return $this;
    }

    /**
     * Get userPreferences
     *
     * @return \UserBundle\Entity\UserPreferences
     */
    public function getUserPreferences()
    {
        return $this->user_preferences;
    }

    /**
     * Set avatar
     *
     * @param \UserBundle\Entity\Avatar $avatar
     *
     * @return User
     */
    public function setAvatar(\UserBundle\Entity\Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \UserBundle\Entity\Avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}
