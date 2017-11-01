<?php

namespace MyStatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MyNanosController extends Controller
{
  public function myNanosAction() {
    $user = $this->getUser();
    $manager = $this->getDoctrine()->getManager();
    $user_pref = $user->getUserPreferences();
    $nano_mode = (null !== $user_pref) ? $user_pref->getNanoMode() : false;

    $nano_repo = $manager->getRepository('MyStatsBundle:MyNanos');
    $current_nano = $nano_repo->findThisMonthNano($user);
    $all_my_nanos = $nano_repo->findBy(array('user' => $user));

    $repo_my_stats = $manager->getRepository('MyStatsBundle:MyDailyStats');
    $this_months_stats = $repo_my_stats->findThisMonthsStats($user);

    $this_nano = array();

    if(null !== $current_nano) {
      $nano_goal = $current_nano->getWordCountGoal();

      // Calcul du nombre de mots écrits ce mois-ci, WW et quota confondus
      $total_nano_words = 0;

      if(null !== $this_months_stats) {
        foreach ($this_months_stats as $stat) {
          $total_nano_words+= $stat->getMyWordsWordCount() + $stat->getWordWarsWordCount();
        }
      }

      // Calcul du quota journalier idéal pour atteindre les 50k en fin de mois
      $now = new \DateTime();
      $today = $now->format('d');
      $nb_days_in_month = cal_days_in_month(CAL_GREGORIAN, $now->format('m'), $now->format('Y'));
      $remaining_words = $nano_goal - $total_nano_words;
      $remaining_days = $nb_days_in_month - $now->format('d');
      $daily_word_goal = (0 !== $remaining_days) ? ceil($remaining_words / $remaining_days) : $remaining_words;

      $this_nano['word_count_goal'] = $current_nano->getWordCountGoal();
      $this_nano['month_word_count'] = $total_nano_words;
      $this_nano['daily_word_goal'] = $daily_word_goal;
      $this_nano['average_word_count'] = floor($total_nano_words / $today);
    }

    $all_nanos = array();

    if(null !== $all_my_nanos) {
      foreach ($all_my_nanos as $nano) {
        $date = $nano->getDate();
        $nb_days_in_month = cal_days_in_month(CAL_GREGORIAN, $date->format('m'), $date->format('Y'));

        // Calcul du nombre de mots écrits ce mois-ci, WW et quota confondus
        $total_nano_words = 0;
        $monthly_stats = $repo_my_stats->findThisMonthsStats($user, $date);

        if(null !== $monthly_stats) {
          foreach ($monthly_stats as $stat) {
            $total_nano_words+= $stat->getMyWordsWordCount() + $stat->getWordWarsWordCount();
          }
        }

        $all_nanos[$nano->getId()]['date'] = $date->format('F Y');
        $all_nanos[$nano->getId()]['word_count_goal'] = $nano->getWordCountGoal();
        $all_nanos[$nano->getId()]['month_word_count'] = $total_nano_words;
        $all_nanos[$nano->getId()]['daily_word_goal'] = $daily_word_goal;
        $all_nanos[$nano->getId()]['average_word_count'] = floor($total_nano_words / $nb_days_in_month);
      }
    }

    return $this->render('MyStatsBundle:MyNanos:my_nanos.html.twig', array(
      'mode_nano' => ($nano_mode) ? 'on' :'off',
      'this_nano' => $this_nano,
      'all_nanos' => $all_nanos
    ));
  }
}