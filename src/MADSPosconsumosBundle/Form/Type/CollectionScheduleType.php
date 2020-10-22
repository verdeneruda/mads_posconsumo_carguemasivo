<?php 

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
* class CollectionScheduleType
*
* @author David Alméciga <walmeciga@minambiente.gov.co>
*/
class CollectionScheduleType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder
          ->add('days', ChoiceType::class, array(
            'required' => true,
            'choices' => array(
                'LUN' => 'LUN',
                'MAR' => 'MAR',
                'MIE' => 'MIE',
                'JUE' => 'JUE',
                'VIE' => 'VIE',
                'SAB' => 'SAB',
                'DOM' => 'DOM',
             ),
            'expanded' => true,
            'multiple' => true,
            'data'     => array('LUN', 'MAR', 'MIE', 'JUE', 'VIE')

          ))
          ->add('openingTime', TimeType::class, array('required' => true,
                                                      'data'     => new \DateTime('08:00')))
          ->add('closingTime', TimeType::class, array('required' => true,
                                                      'data'     => new \DateTime('17:00')))
          ->add('continuousDay', CheckboxType::class, array('required' => false,
                                                            'data'     => true))
      ;
  }

  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'MADSPosconsumosBundle\Entity\Schedule'
    ));
  }    
}