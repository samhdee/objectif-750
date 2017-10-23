<?php

namespace WordWarsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class WordWarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->add('title', TextType::class, array(
          'required' => false,
          'attr' => array('placeholder' => 'Facultatif mais toujours pratique')));

      $now = date('H');
      $hours = array($now, ($now+1)%24, ($now+2)%24, ($now+3)%24);
      $minutes = array(0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55);

      $builder->add('start', TimeType::class, array(
        'widget' => 'choice',
        'input' => 'datetime',
        'hours' => $hours,
        'minutes' => $minutes));

      $builder->add('end', TimeType::class, array(
        'widget' => 'choice',
        'input' => 'datetime',
        'hours' => $hours,
        'minutes' => $minutes));

      $builder->add('save', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WordWarsBundle\Entity\WordWar'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'wordwarsbundle_wordwar';
    }


}
