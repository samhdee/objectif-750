<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Avatar
 *
 * @ORM\Table(name="avatar")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\AvatarRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Avatar
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
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    private $file;
    private $temp_filename;

    public function __toString() {
      return $this->id . '.' . $this->extension;
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
     * Get name
     *
     * @return string
     */
    public function getAvatarPath()
    {
      return $this->getUploadRootDir() . '/' . $this->id . '.' . $this->extension;
    }

    public function getFile()
    {
      return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
      $this->file = $file;

      // On vérifie si on avait déjà un avatar pour ce user
      if(null !== $this->extension) {
        // On sauvegarde le nom de l'ancien avatar pour le supprimer par la suite
        $this->temp_filename = $this->extension;

        $this->extension = null;
        $this->alt = null;
      }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
      // Pas d'avatar, pas d'avatar
      if(null === $this->file) {
        return;
      }

      $this->extension = $this->file->guessExtension();
      $this->alt = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
      // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
      if (null === $this->file) {
        return;
      }

      // On supprime l'ancien avatar désormais inutile
      if(null !== $this->temp_filename) {
        $old_file = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->temp_filename;

        if(file_exists($old_file)) {
          unlink($old_file);
        }
      }

      // On déplace le fichier envoyé dans le répertoire qui va bien
      $this->file->move(
        $this->getUploadRootDir(),
        $this->id . '.' . $this->extension
      );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload() {
      // On sauvegarde le nom du fichier pour pouvoir le supprimer ensuite
      $this->temp_filename = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->extension;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
      if(file_exists($this->temp_filename)) {
        unlink($this->temp_filename);
      }
    }

    public function getUploadDir()
    {
      // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
      return 'uploads/avatars';
    }

    protected function getUploadRootDir()
    {
      // On retourne le chemin relatif vers l'image pour notre code PHP
      return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return Avatar
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Avatar
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }
}
