<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserPreferencesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->add('wordCountGoal', TextType::class, array('label' => 'Votre objectif'));

      $builder->add(
        'periodGoal',
        ChoiceType::class, array(
          'choices' => array(
            'day' => 'jour',
            'week' => 'semaine',
            'month' => 'mois'
          ),
          'label' => 'par'
        )
      );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults(array(
        'data_class' => 'UserBundle\Entity\UserPreferences'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
      return 'userbundle_userpreferences';
    }


  }
