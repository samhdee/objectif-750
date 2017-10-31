<?php
namespace WordWarsBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class WordWarsAdmin extends AbstractAdmin
{
  // Fields to be shown on create/edit forms
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('title')
      ->add('start')
      ->add('end');
  }

    // Fields to be shown on filter forms
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('title')
      ->add('start')
      ->add('author');
  }

    // Fields to be shown on lists
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('title')
      ->add('start')
      ->add('author');
  }

      // Fields to be shown on show action
  protected function configureShowFields(ShowMapper $showMapper)
  {
    $showMapper
      ->add('title')
      ->add('start')
      ->add('author');
  }
}
