<?php

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use MADSPosconsumosBundle\Enum\EntitiesTypes;

/**
 * Class EntityTypeType.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class EntityTypeType extends AbstractType
{

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
  	$resolver->setDefaults(array('required' => true, 'choices' => EntitiesTypes::getChoices()));
  }

  public function getParent()
  {
    return ChoiceType::class;
  }

}