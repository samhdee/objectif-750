<?php

namespace MyStatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyStatsController extends Controller
{
  public function dailyStatsAction()
  {
    // Récupération des objets nécessaires
    $user = $this->getUser();
    $user_pref = $user->getUserPreferences();
    $manager = $this->getDoctrine()->getManager();
    $repo_my_stats = $manager->getRepository('MyStatsBundle:MyDailyStats');
    $todays_stats = $repo_my_stats->findTodaysStats($user);
    $all_stats = $repo_my_stats->findThisMonthsStats(array('user' => $user));

    $word_count_goal = (null !== $user_pref) ? $user_pref->getWordCountGoal() : 0;
    $total_words_written = ($todays_stats) ? ($todays_stats->getMyWordsWordCount() + $todays_stats->getWordWarsWordCount()) : 0;
    $validay = ($word_count_goal <= $total_words_written && 0 !== $total_words_written);

    // Récupération du nombre de jour dans le mois en cours
    $now = new \DateTime(date('Y-m') . '-01');
    $month = $now->format('m');
    $year = $now->format('Y');
    $number_day_of_week = $now->format('N');
    $nb_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $calendar_data = array();

    // Construction des data du calendrier
    foreach ($all_stats as $stat) {
      $total_mots = $stat->getMyWordsWordCount() + $stat->getWordWarsWordCount();
      $goal = $stat->getDaysGoal();
      $percent = ($goal != 0) ? floor(($total_mots * 100) / $goal) : 0;
      $calendar_data[$stat->getDate()->format('Y-m-d')] = array(
        'nb_mots' => $total_mots,
        'goal' => $goal,
        'progression' => ($percent <= 100) ? $percent : 100,
        'class' => (100 <= $percent) ? 'allwords' : (0 < $percent) ? 'somewords' : 'nowords',
        'nb_day' => $stat->getDate()->format('d'));
    }

    // Ajout des jours où ya rien eu
    for($i = 1 ; $i <= $nb_days ; $i++) {
      $temp = new \DateTime($year . '-' . $month . '-' . $i);
      $temp_date = $temp->format('Y-m-d');
      $temp_day = $temp->format('d');

      if(!isset($calendar_data[$temp_date])) {
        $calendar_data[$temp_date] = array(
          'nb_mots' => 0,
          'goal' => 0,
          'progression' => 0,
          'class' => 'nowords',
          'nb_day' => $temp_day);
      }
    }

    ksort($calendar_data);

    return $this->render('MyStatsBundle:MyStats:dailywordsstats.html.twig', array(
      'validay' => $validay,
      'total_words_written' => $total_words_written,
      'word_count_goal' => $word_count_goal,
      'calendar_data' => $calendar_data
    ));
  }

  public function wwStatsAction() {
    return $this->render('MyStatsBundle:MyStats:wwstats.html.twig');
  }
}
