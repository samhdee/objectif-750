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

    if(null === $inactive_filters || !in_array('solo', $inactive_filters)) {
      $my_words_repo = $manager->getRepository('MyWordsBundle:DailyWords');
      $my_words = $my_words_repo->findBy(array('user' => $user));

      foreach ($my_words as $my_word) {
        $date_words = $my_word->getDate();
        $temp = array('content' => html_entity_decode($my_word->getContent()), 'type' => 'solo');
        $contents[$date_words->format('Y-m-d')][] = $temp;
      }
    }

    if(null === $inactive_filters || !in_array('ww', $inactive_filters)) {
      $my_word_war_repo = $manager->getRepository('WordWarsBundle:MyWordWar');
      $my_word_wars = $my_word_war_repo->findBy(array('user' => $user));

      foreach ($my_word_wars as $my_ww) {
        $date_ww = $my_ww->getWordWar()->getStart();
        $temp = array('content' => html_entity_decode($my_ww->getContent()), 'type' => 'ww');
        $contents[$date_ww->format('Y-m-d')][] = $temp;
      }
    }

    krsort($contents);

    return $contents;
  }
}