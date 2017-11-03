<?php

namespace MyStatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use MyWordsBundle\Entity\DailyWords;

class ArchivesController extends Controller
{
  public function myArchivesAction(Request $request, $page) {
    $result = $this->get_my_texts($request, $page);

    return $this->render('MyStatsBundle:Archives:my_archives.html.twig', array(
      'contents' => $result['contents'],
      'nb_pages' => $result['nb_pages'],
      'current_page' => $page,
      'nb_entries' => $result['nb_entries']
      )
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

      $result = $this->get_my_texts($request);

      return $this->render('MyStatsBundle:Archives:archives_content.html.twig', array(
        'contents' => $result['contents'],
        'nb_pages' => $result['nb_pages'],
        'current_page' => 1,
        'nb_entries' => $result['nb_entries']
        )
      );
    }
  }

  private function get_my_texts(Request $request, $page = 1) {
    // Récupération des filtres dans la session
    $session = $request->getSession();
    $inactive_filters = $session->get('inactive_filters');

    $user = $this->getUser();
    $manager = $this->getDoctrine()->getManager();

    $contents = array();

    $my_words_repo = $manager->getRepository('MyWordsBundle:DailyWords');
    $my_words = $my_words_repo->findWordsByFilters($user, $inactive_filters, $page);
    $nb_entries = $my_words_repo->getTotalNbEntriesWithFilters($user, $inactive_filters);
    $nb_pages = ($nb_entries > DailyWords::NUM_ENTRIES) ? ceil($nb_entries / DailyWords::NUM_ENTRIES) : 1;

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

    $result['contents'] = $contents;
    $result['nb_pages'] = $nb_pages;
    $result['nb_entries'] = $nb_entries;

    return $result;
  }
}