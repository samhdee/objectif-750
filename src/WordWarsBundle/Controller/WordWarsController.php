<?php

namespace WordWarsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use WordWarsBundle\Entity\WordWar;
use WordWarsBundle\Entity\MyWordWar;
use MyStatsBundle\Entity\MyDailyStats;
use WordWarsBundle\Form\WordWarType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WordWarsController extends Controller
{
  public function indexAction($id) {
    return $this->render('WordWars:MyWords:index.html.twig');
  }

  public function newAction(Request $request)
  {
    $user = $this->getUser();
    $word_war = new WordWar($user);

    $form = $this->get('form.factory')->create(WordWarType::class, $word_war);

    if($request->isMethod('POST')) {
      $form->handleRequest($request);

      // Sauvegarde de la nouvelle WW
      if($form->isValid()) {
        // Formattage de la date pour qu'à l'heure sélectionnée s'ajoute la date du jour
        $word_war->setStart(new \DateTime($word_war->getStart()->format('H:i:s')));
        $word_war->setEnd(new \DateTime($word_war->getEnd()->format('H:i:s')));
        // Persistage de la word war nouvellement créée
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($word_war);
        $manager->flush();

        // On fournit l'url de ladite WW pour la partager
        $request
          ->getSession()
          ->getFlashBag()
          ->add(
            'status',
            'Votre Word War a été créée, vous pouvez partager le lien suivant pour inviter vos amis : ' . $this->generateUrl('word_wars_in_progress', array('id' => $word_war->getId()), UrlGeneratorInterface::ABSOLUTE_URL) . '.');

        // Et on redirige vers l'espace de WW
        return $this->redirectToRoute('word_wars_in_progress', array('id' => $word_war->getId()));
      }
    }

    return $this->render('WordWarsBundle:WordWars:new.html.twig', array('form' => $form->createView()));
  }

  public function inProgressAction($id) {
    // Récupération du user en cours
    $user = $this->getUser();
    $manager = $this
      ->getDoctrine()
      ->getManager();

    // Récupération de la WW
    $ww_repo = $manager->getRepository('WordWarsBundle:WordWar');
    $word_war = $ww_repo->find($id);

    // On vérifie avant tout qu'elle est toujours en cours
    $date = new \DateTime();

    if($word_war->getEnd() < $date) {
      return $this->render('WordWarsBundle:WordWars:ww_ended.html.twig');
    }

    // Récupération du repo my_word_war
    $repo = $manager->getRepository('WordWarsBundle:MyWordWar');
    $my_word_war = $repo->findByWWId($id, $user);

    $saved_words = '';
    $word_count = 0;

    // Si on a déjà des mots pour la journée, on les affiche dans le textarea
    if(null !== $my_word_war) {
      $saved_words = html_entity_decode($my_word_war->getContent());
      $word_count = $my_word_war->getWordCount();
    }
    else {
      $my_word_war = new MyWordWar($word_war, $user);
      $my_word_war->setContent('');
      $my_word_war->setWordCount(0);
      $manager->persist($my_word_war);
      $manager->flush();
    }

    $date_end = $word_war->getEnd();
    $end_time = $date_end->format('Y-m-d H:i');

    return $this->render(
      'WordWarsBundle:WordWars:in_progress.html.twig',
      array(
        'saved_words' => $saved_words,
        'word_count' => $word_count,
        'end_time' => $end_time,
        'ww_id' => $word_war->getId(),
        )
      );
  }

  public function saveAction(Request $request)
  {
    // On vérifie qu'il s'agit d'une requête AJAX
    // (normalement le router vérifie que c'est bien une requête POST)
    if($request->isXmlHttpRequest()) {
      // Récupération des mots et du word_count
      $ww_id = $request->request->get('ww_id');
      $post = $request->request->get('content');
      $word_count = $request->request->get('word_count');

      // Récupération du user
      $user = $this->getUser();
      $manager = $this
        ->getDoctrine()
        ->getManager();

      // Récupération des repos word_war, daily_words et stats
      $words_repo = $manager->getRepository('WordWarsBundle:MyWordWar');
      $daily_stats_repo = $manager->getRepository('MyStatsBundle:MyDailyStats');
      $ww_repo = $manager->getRepository('WordWarsBundle:WordWar');

      // Récupération des
      $word_war = $ww_repo->find($ww_id);
      $ww_words = $words_repo->findByWWId($ww_id, $user);
      $stats = $daily_stats_repo->findTodaysStats($user);

      // Si ce sont les premiers mots de la journée, on crée la ligne du jour
      if(null === $ww_words) {
        $ww_words = new MyWordWar($word_war, $user);
        $manager->persist($ww_words);
      }

      if(null === $stats) {
        $stats = new MyDailyStats($user);
        $manager->persist($stats);
      }

      // Mise à jour des mots du jour
      $ww_words->setContent(htmlentities($post));
      $ww_words->setWordCount($word_count);

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
