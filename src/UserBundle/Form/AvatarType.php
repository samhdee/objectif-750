<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AvatarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->add(
        'file',
        FileType::class,
        array(
          'required' => false,
          'label' => 'profile.upload.avatar'
        )
      );

      // $builder->addEventListener(
      // FormEvents::PRE_SET_DATA,
      // function(FormEvent $event) {
      //   $groupe = $event->getData();

      //   if (null === $groupe) {
      //     return;
      //   }
      // });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Avatar'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_avatar';
    }


}
