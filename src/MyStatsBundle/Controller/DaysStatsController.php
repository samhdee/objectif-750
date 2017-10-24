<?php

namespace MyStatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DaysStatsController extends Controller
{
  public function daysProgressAction() {
    $user = $this->getUser();
    $manager = $this->getDoctrine()->getManager();
    $repo_my_stats = $manager->getRepository('MyStatsBundle:MyDailyStats');
    $todays_stats = $repo_my_stats->findTodaysStats($user);
    $user_pref = $user->getUserPreferences();

    // Init des variables
    $nano_mode = (null !== $user_pref) ? $user_pref->getNanoMode() : false;
    $progress = array('nano_mode' => ($nano_mode) ? 'on' :'off', 'no_pref_set' => (null === $user_pref));
    $todays_word_count = (null !== $todays_stats) ? $todays_stats->getMyWordsWordCount() + $todays_stats->getWordWarsWordCount() : 0;
    $todays_word_goal = (null !== $user_pref) ? $user_pref->getWordCountGoal() : 0;
    $todays_percent = (0 !== $todays_word_goal) ? ($todays_word_count * 100 / $todays_word_goal) : 0;

    // Récupération des stats nano, si nano en cours
    if($nano_mode) {
      // Init des variables
      $total_nano_words = 0;
      $percent_nano_accomplished = $todays_word_count * 100 / 50000;

      // Récupération des données en base
      $repo_my_nanos = $manager->getRepository('MyStatsBundle:MyNanos');
      $nano = $repo_my_nanos->findThisMonthNano($user);
      $this_months_stats = $repo_my_stats->findThisMonthsStats($user);
      // var_dump($this_months_stats); die;
      $progress['nano_stats'] = array('is_nano_started' => ($nano !== null));

      // Calcul du nombre de mots écrits ce mois-ci, WW et quota confondus
      if(null !== $this_months_stats) {
        foreach ($this_months_stats as $stat) {
          $total_nano_words+= $stat->getMyWordsWordCount() + $stat->getWordWarsWordCount();
        }
      }

      // Calcul du quota journalier idéal pour atteindre les 50k en fin de mois
      $now = new \DateTime();
      $todays_day = $now->format('d');
      $todays_month = $now->format('m');
      $todays_year = $now->format('Y');
      $nb_days_in_month = cal_days_in_month(CAL_GREGORIAN, $todays_month, $todays_year);

      // Modif du word goal quotidien pour coller à l'objectif mensuel
      $remaining_words = 50000 - $total_nano_words;
      $remaining_days = $nb_days_in_month - $todays_day;
      $todays_word_goal = ceil($remaining_words / $remaining_days);
      $todays_percent = $todays_word_count / $todays_word_goal;

      // Stockage des stats mensuelles
      $progress['nano_stats']['total_nano_words'] = $total_nano_words;
      $progress['nano_stats']['percent_nano_accomplished'] = $percent_nano_accomplished;
      $progress['nano_stats']['nano_word_goal'] = 50000;
    }

    // Stockage des stats journalières
    $progress['days_stats']['todays_word_count'] = $todays_word_count;
    $progress['days_stats']['todays_word_goal'] = $todays_word_goal;
    $progress['days_stats']['todays_percent_accomplished'] = $todays_percent;

    return $this->render(
      'MyStatsBundle:DaysStats:days_progress.html.twig',
      array('progress' => $progress)
    );
  }
}