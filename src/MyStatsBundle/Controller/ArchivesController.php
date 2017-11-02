<?php

namespace MyStatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArchivesController extends Controller
{
  public function myArchivesAction(Request $request) {
    $contents = $this->get_my_texts($request);

    return $this->render('MyStatsBundle:Archives:my_archives.html.twig',
      array('contents' => $contents)
    );
  }

  public function filterAction(Request $request) {
    // On vérifie qu'il s'agit d'une requête AJAX
    // (normalement le router vérifie que c'est bien une requête POST)
    if($request->isXmlHttpRequest()) {
      // Met les filtres en session et indique au JS que c'est OK pour reload la page
      $types = $request->request->get('types');

      $session = $request->getSession();
      $session->set('inactive_filters', $types);

      $contents = $this->get_my_texts($request);

      return $this->render('MyStatsBundle:Archives:archives_content.html.twig',
        array('contents' => $contents)
      );
    }
  }

  private function get_my_texts(Request $request) {
    // Récupération des filtres dans la session
    $session = $request->getSession();
    $inactive_filters = $session->get('inactive_filters');

    $user = $this->getUser();
    $manager = $this->getDoctrine()->getManager();

    $contents = array();

    $my_words_repo = $manager->getRepository('MyWordsBundle:DailyWords');
    $my_words = $my_words_repo->findWordsByFilters($user, $inactive_filters);

    foreach ($my_words as $my_word) {
      $date_words = $my_word->getDate();
      $temp = array(
        'content' => html_entity_decode($my_word->getContent()),
        'type' => $my_word->getType(),
        'id' => $my_word->getId()
      );
      $contents[$date_words->format('Y-m-d')][] = $temp;
    }

    krsort($contents);

    return $contents;
  }
}