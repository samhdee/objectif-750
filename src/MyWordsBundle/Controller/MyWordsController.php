<?php

namespace MyWordsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use MyWordsBundle\Entity\DailyWords;
use MyStatsBundle\Entity\MyDailyStats;

class MyWordsController extends Controller
{
  public function indexAction()
  {
    // Récupération du user en cours et goal du jour
    $user = $this->getUser();
    $todays_goal = $user->getUserPreferences()->getWordCountGoal();

    // Récupération du repo words
    $manager = $this
      ->getDoctrine()
      ->getManager();
    $repo = $manager->getRepository('MyWordsBundle:DailyWords');
    $words = $repo->findTodaysWords($user);

    $todays_saved_words = '';
    $word_count = 0;

    // Si on a déjà des mots pour la journée, on les affiche dans le textarea
    if(null !== $words) {
      $todays_saved_words = html_entity_decode($words->getContent());
      $word_count = $words->getWordCount();
    }

    return $this->render(
      'MyWordsBundle:MyWords:dailywords.html.twig',
      array(
        'saved_words' => $todays_saved_words,
        'word_count' => $word_count,
        'todays_goal' => $todays_goal
      )
    );
  }

  public function saveAction(Request $request)
  {
    // On vérifie qu'il s'agit d'une requête AJAX
    // (normalement le router vérifie que c'est bien une requête POST)
    if($request->isXmlHttpRequest()) {
      // Récupération des mots et du word_count
      $post = $request->request->get('content');
      $word_count = $request->request->get('word_count');

      // Récupération du user
      $user = $this->getUser();
      $manager = $this
        ->getDoctrine()
        ->getManager();

      // Récupération des repos daily_words et stats
      $daily_word_repo = $manager->getRepository('MyWordsBundle:DailyWords');
      $daily_stats_repo = $manager->getRepository('MyStatsBundle:MyDailyStats');

      $words = $daily_word_repo->findTodaysWords($user);
      $stats = $daily_stats_repo->findTodaysStats($user);

      // Si ce sont les premiers mots de la journée, on crée la ligne du jour
      if(null === $words) {
        $words = new DailyWords($user);
        $manager->persist($words);
      }

      if(null === $stats) {
        $stats = new MyDailyStats($user);
        $manager->persist($stats);
      }

      // Mise à jour des mots du jour
      $words->setDate(new \Datetime());
      $words->setContent(htmlentities($post));
      $words->setWordCount($word_count);
      $words->setTodaysGoal($user->getUserPreferences()->getWordCountGoal());

      // Mise à jour des stats relatives aux mots du jour
      $stats->setDate(new \Datetime());
      $stats->setDaysGoal($user->getUserPreferences()->getWordCountGoal());
      $stats->setMyWordsWordCount($word_count);

      // Sauvegarde en base
      $manager->flush();

      $response = new JsonResponse(array(
        'status' => 'ok',
        'message' => 'progression sauvegardée'));
    }
    else {
      $response = new JsonResponse(array(
        'status' => 'ko',
        'message' => 'problème lors de la sauvegarde'));
    }

    return $response;
  }
}
