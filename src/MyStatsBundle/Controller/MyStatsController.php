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
    $manager = $this->getDoctrine()->getManager();
    $repo_my_stats = $manager->getRepository('MyStatsBundle:MyDailyStats');
    $todays_stats = $repo_my_stats->findTodaysStats($user);
    $todays_word_count = $user->getUserPreferences()->getWordCountGoal();
    $all_stats = $repo_my_stats->findThisMonthsStats(array('user' => $user));

    $validay = false;
    $total_words_written = 0;

    if($todays_stats) {
      $total_words_written = $todays_stats->getMyWordsWordCount() + $todays_stats->getWordWarsWordCount();

      if($todays_word_count <= $total_words_written) {
        $validay = true;
      }
    }

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
      'word_count_goal' => $user->getUserPreferences()->getWordCountGoal(),
      'calendar_data' => $calendar_data
    ));
  }

  public function wwStatsAction() {
    return $this->render('MyStatsBundle:MyStats:wwstats.html.twig');
  }

  public function daysProgressAction() {
    $user = $this->getUser();
    $manager = $this->getDoctrine()->getManager();

    $user_pref = $user->getUserPreferences();
    $nano_mode = false;

    if(null !== $user_pref) {
      $nano_mode = $user_pref->getNanoMode();
    }

    // Version texte du booléen nano_mode
    $nano_mode_text = ($nano_mode) ? 'on' :'off';

    $repo_my_stats = $manager->getRepository('MyStatsBundle:MyDailyStats');
    $todays_stats = $repo_my_stats->findTodaysStats($user);
    $this_months_stats = $repo_my_stats->findThisMonthsStats($user);

    $progress = array('nano_mode' => $nano_mode_text, 'no_pref_set' => false);

    $todays_word_count = (null !== $todays_stats) ? $todays_stats->getMyWordsWordCount() + $todays_stats->getWordWarsWordCount() : 0;

    $todays_percent = $todays_word_count * 100 / $todays_word_goal;

    if(null !== $user_pref) {
      $todays_word_goal = $user_pref->getWordCountGoal();
    }
    else {
      $progress['no_pref_set'] = true;
    }

    // Le mode nano contient plus de stats que le mode normal
    if($nano_mode) {
      $percent_nano_accomplished = $total_nano_words = 0;
      $repo_my_nanos = $manager->getRepository('MyStatsBundle:MyNanos');

      $nano = $repo_my_nanos->findThisMonthNano($user);

      $progress['nano_stats'] = array('nano_mode' => $nano_mode_text);
      $progress['is_nano_started'] = ($nano !== null);

      if(null !== $this_months_stats) {
        // On remonte tous les mots écrits pour ce nano
        if(null !== $this_months_stats) {
          foreach ($this_months_stats as $stat) {
            $total_nano_words+= $stat->getMyWordsWordCount() + $stat->getWordWarsWordCount();
          }
        }

        $percent_nano_accomplished = $todays_word_count * 100 / 50000;
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

      $progress['nano_stats']['total_nano_words'] = $total_nano_words;
      $progress['nano_stats']['percent_nano_accomplished'] = $percent_nano_accomplished;
      $progress['nano_stats']['nano_word_goal'] = 50000;
    }

    $progress['regular_stats']['todays_word_goal'] = $todays_word_goal;
    $progress['regular_stats']['todays_word_count'] = $todays_word_count;
    $progress['regular_stats']['todays_percent_accomplished'] = $todays_percent;

    return $this->render(
      'MyStatsBundle:MyStats:days_progress.html.twig',
      array('progress' => $progress)
    );
  }
}
