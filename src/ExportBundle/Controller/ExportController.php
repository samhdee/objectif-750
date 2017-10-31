<?php

namespace ExportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExportController extends Controller
{
    public function archivesAction()
    {
        return $this->render('ExportBundle:Export:archives.html.twig');
    }
}
