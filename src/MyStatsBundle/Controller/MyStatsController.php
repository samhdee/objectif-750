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
    $user = $this->getUser();
    $manager = $this->getDoctrine()->getManager();
    $repo_my_stats = $manager->getRepository('MyStatsBundle:MyDailyStats');
    $todays_stats = $repo_my_stats->findTodaysStats($user);
    $todays_word_count = $user->getUserPreferences()->getWordCountGoal();
    $all_stats = $repo_my_stats->findBy(array('user' => $user));

    $validay = false;
    $total_words_written = 0;

    if($todays_stats) {
      $total_words_written = $todays_stats->getMyWordsWordCount() + $todays_stats->getWordWarsWordCount();

      if($todays_word_count <= $total_words_written) {
        $validay = true;
      }
    }

    $now = new \DateTime();
    $month = $now->format('m');
    $year = $now->format('Y');
    $number_day_of_week = $now->format('N');
    $nb_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $calendar_data = array();

    foreach ($all_stats as $stat) {
      $total_mots = $stat->getMyWordsWordCount() + $stat->getWordWarsWordCount();
      $goal = $stat->getDaysGoal();
      $date = $stat->getDate()->format('Y-m-d');
      $day = $stat->getDate()->format('d');
      $calendar_data[$date] = array(
        'nb_mots' => $total_mots,
        'goal' => $goal,
        'progression' => ($goal != 0) ? ($total_mots * 100) / $goal : 0,
        'class' => ($goal <= $total_mots) ? 'allwords' : ($total_mots == 0) ? 'nowords' : 'somewords',
        'nb_day' => $day);
    }

    for($i = 1 ; $i <= $nb_days ; $i++) {
      if($i < $number_day_of_week) {
        for($j = $number_day_of_week ; $j >= 0 ; $j--) {
          if($month == '01') {
            $temp_month = '12';
            $temp_year--;
          }
          else {
            $temp_year = $year;
            $temp_month = $month -1;
          }

          $temp_nb_days = cal_days_in_month(CAL_GREGORIAN, $temp_month, $temp_year);
          $temp_day = $temp_nb_days - $j;
          $temp = new \DateTime($temp_year . '-' . $temp_month . '-' . $temp_day);
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

  public function WWStatsAction() {
    return $this->render('MyStatsBundle:MyStats:wwstats.html.twig');
  }
}
