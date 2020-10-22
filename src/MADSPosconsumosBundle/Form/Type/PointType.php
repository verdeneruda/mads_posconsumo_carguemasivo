<?php 

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;

/**
* class PointType
*
* @author David Alméciga <walmeciga@minambiente.gov.co>
*/
class PointType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder
          ->add('latitude', HiddenType::class, array('required' => true))
          ->add('longitude', HiddenType::class, array('required' => true))
      ;
  }
	
  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
      $resolver->setDefaults(array(
        'data_class' => 'MADSPosconsumosBundle\Model\Point'
      ));
  }
	
  /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'point';
    }
}