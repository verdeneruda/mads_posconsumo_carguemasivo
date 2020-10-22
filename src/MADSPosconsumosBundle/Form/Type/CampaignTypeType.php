<?php

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use MADSPosconsumosBundle\Enum\CampaignsTypes;

/**
 * Class CampaignTypeType.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class CampaignTypeType extends AbstractType
{

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
  	$resolver->setDefaults(array('required' => true, 'choices' => CampaignsTypes::getChoices()));
  }

  public function getParent()
  {
    return ChoiceType::class;
  }

}