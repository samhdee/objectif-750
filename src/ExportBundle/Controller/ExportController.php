<?php

namespace ExportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExportController extends Controller
{
    public function indexAction()
    {
        return $this->render('ExportBundle:Export:index.html.twig');
    }
}
