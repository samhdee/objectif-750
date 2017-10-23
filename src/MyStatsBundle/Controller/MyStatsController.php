<?php

namespace MyStatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyStatsController extends Controller
{
  public function indexAction() {
    return $this->render('MyStatsBundle:MyStats:index.html.twig');
  }

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
    $validay = ($word_count_goal <= $total_words_written);

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
      $calendar_data[$stat->getDate()->format('Y-m-d')] = array(
        'nb_mots' => $total_mots,
        'goal' => $goal,
        'progression' => ($goal != 0) ? ($total_mots * 100) / $goal : 0,
        'class' => ($goal <= $total_mots) ? 'allwords' : ($total_mots == 0) ? 'nowords' : 'somewords',
        'nb_day' => $stat->getDate()->format('d'));
    }

    if($month == '01') {
      $prev_month = '12';
      $prev_year--;
    }
    else {
      $prev_year = $year;
      $prev_month = $month -1;
    }

    $nb_days_prev_month = cal_days_in_month(CAL_GREGORIAN, $prev_month, $prev_year);

    // Ajout des jours où ya rien eu
    for($i = 1 ; $i <= $nb_days ; $i++) {
      // Si le premier jour du mois n'est pas un lundi
      if($i < $number_day_of_week) {
        // On rajoute des cases filler jusqu'au premier jour du mois
        for($j = ($number_day_of_week - 1) ; $j >= 1 ; $j--) {
          $temp_day = $nb_days_prev_month - $j;
          $temp = new \DateTime($prev_year . '-' . $prev_month . '-' . $temp_day);
          $temp = $temp->format('Y-m-d');

          $calendar_data[$temp] = array(
            'nb_mots' => 0,
            'goal' => 0,
            'progression' => 0,
            'class' => 'filler',
            'nb_day' => '');
        }
      }

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
      $progress['nano_stats'] = array('nano_mode' => $nano_mode_text, 'is_nano_started' => ($nano !== null));
      $percent_nano_accomplished = $todays_word_count * 100 / 50000;

      // Récupération des données en base
      $repo_my_nanos = $manager->getRepository('MyStatsBundle:MyNanos');
      $nano = $repo_my_nanos->findThisMonthNano($user);
      $this_months_stats = $repo_my_stats->findThisMonthsStats($user);

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
    $progress['days_stats']['todays_word_goal'] = $todays_word_goal;
    $progress['days_stats']['todays_word_count'] = $todays_word_count;
    $progress['days_stats']['todays_percent_accomplished'] = $todays_percent;

    return $this->render(
      'MyStatsBundle:MyStats:days_progress.html.twig',
      array('progress' => $progress)
    );
  }
}
