<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
  public function contactAction() {
    return $this->render('CoreBundle:Core:contact.html.twig');
  }
}