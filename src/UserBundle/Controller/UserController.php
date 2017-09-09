<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
  public function indexAction()
  {
    $message = $this->get('translator')->trans('message.test');
    return $this->render('UserBundle:User:index.html.twig', array('test' => $message));
  }
}
