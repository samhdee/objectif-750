<?php
// src/OC/UserBundle/DataFixtures/ORM/LoadUser.php
// php bin/console doctrine:fixtures:load

namespace OC\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listUsers = array(
      array('username' => 'admin', 'password' => 'admin', 'roles' => array('ROLE_ADMIN')),
      array('username' => 'user', 'password' => 'user', 'roles' => array('ROLE_USER'))
    );

    foreach ($listUsers as $a_user) {
      // On crée l'utilisateur
      $user = new User;

      // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
      $user->setUsername($a_user['username']);
      $user->setPassword($a_user['password']);
      $user->setRoles($a_user['roles']);
      $user->setEmail('mail@example.com');
      $user->setEnabled(1);

      // On le persiste
      $manager->persist($user);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}