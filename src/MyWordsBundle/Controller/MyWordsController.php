<?php

namespace MyWordsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use MyStatsBundle\Entity\MyDailyStats;
use MyStatsBundle\Entity\MyNanos;
use MyWordsBundle\Entity\DailyWords;

class MyWordsController extends Controller
{
  public function indexAction()
  {
    // Récupération du user en cours et goal du jour
    $user = $this->getUser();
    $user_pref = $user->getUserPreferences();
    $manager = $this
      ->getDoctrine()
      ->getManager();
    $todays_goal = (null !== $user_pref) ? $user_pref->getWordCountGoal() : 0;

    if(null !== $user_pref && $user_pref->getNanoMode()) {
      $repo_my_stats = $manager->getRepository('MyStatsBundle:MyDailyStats');
      $this_months_stats = $repo_my_stats->findThisMonthsStats($user);

      $total_nano_words = 0;

      // Calcul du nombre de mots écrits ce mois-ci, WW et quota confondus
      if(null !== $this_months_stats) {
        foreach ($this_months_stats as $stat) {
          $total_nano_words+= $stat->getMyWordsWordCount() + $stat->getWordWarsWordCount();
        }
      }

      $now = new \DateTime();
      $nb_days_in_month = cal_days_in_month(CAL_GREGORIAN, $now->format('m'), $now->format('Y'));

      $remaining_words = 50000 - $total_nano_words;
      $remaining_days = $nb_days_in_month - ($now->format('d') - 1);
      $todays_goal = (0 !== $remaining_days) ? ceil($remaining_words / $remaining_days) : $remaining_words;
    }

    // Récupération du repo words
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
      $my_nanos_repo = $manager->getRepository('MyStatsBundle:MyNanos');

      $words = $daily_word_repo->findTodaysWords($user);
      $stats = $daily_stats_repo->findTodaysStats($user);
      $nano = $my_nanos_repo->findThisMonthNano($user);

      $today = new \Datetime();

      // Si ce sont les premiers mots de la journée, on crée la ligne du jour
      if(null === $words) {
        $words = new DailyWords($user);
        $manager->persist($words);
      }

      if(null === $stats) {
        $stats = new MyDailyStats($user);
        $manager->persist($stats);
      }

      if(null === $nano) {
        $nano = new MyNanos($user);
        $manager->persist($nano);
      }

      // Mise à jour des mots du jour
      $words->setDate($today);
      $words->setContent(htmlentities($post));
      $words->setWordCount($word_count);

      // Mise à jour des stats relatives aux mots du jour
      $stats->setDate($today);
      $stats->setDaysGoal($user->getUserPreferences()->getWordCountGoal());
      $stats->setMyWordsWordCount($word_count);

      // Sauvegarde en base
      $manager->flush();

      $days_progress = $this->forward('MyStatsBundle:DaysStats:daysProgress');

      $response = new JsonResponse(array(
        'status' => 'ok',
        'message' => 'progression sauvegardée',
        'days_progress' => $days_progress->getContent()
      ));
    }
    else {
      $response = new JsonResponse(array(
        'status' => 'ko',
        'message' => 'problème lors de la sauvegarde'));
    }

    return $response;
  }
}
