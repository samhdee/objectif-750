<?php

namespace WordWarsBundle\Form;

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
      $builder->add('title', TextType::class);

      $now = date('H');
      $hours = array($now, $now+1, $now+2, $now+3);

      $builder->add('start', TimeType::class, array(
        'widget' => 'choice',
        'input' => 'datetime',
        'hours' => $hours,
        'minutes' => array(0, 15, 30)));

      $builder->add('end', TimeType::class, array(
        'widget' => 'choice',
        'input' => 'datetime',
        'hours' => $hours,
        'minutes' => array(0, 15, 30)));

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
