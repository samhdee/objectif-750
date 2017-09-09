<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Collection;

class AdminController extends Controller
{
  public function indexAction()
  {
    return $this->render('AdminBundle:Admin:index.html.twig');
  }

  public function usersAction($page)
  {
    $manager = $this->getDoctrine()->getManager()->getRepository('UserBundle:User');
    $users = $manager->findRangedUsers($page);

    return $this->render('AdminBundle:Admin:users.html.twig', array('users' => $users));
  }
}
