<?php

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

use JavierEguiluz\Bundle\EasyAdminBundle\Form\Type\EasyAdminFormType;

use MADSPosconsumosBundle\Form\Validator\CollectionPointValidationGroupResolver;
use MADSPosconsumosBundle\Model\Point;
use CrEOF\Spatial\PHP\Types\Geometry\Point as BasePoint;

/**
 * Class CollectionPointFormType.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class CollectionPointFormType extends AbstractType
{

  private $groupResolver;

  public function __construct(CollectionPointValidationGroupResolver $groupResolver)
  {
      $this->groupResolver = $groupResolver;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder->get('datetime')->setData(new \DateTime("now"));
      $builder->get('point')
              ->addModelTransformer(new CallbackTransformer(
                  function ($point) {
                      if (!$point instanceof BasePoint) {
                        return;
                      }
                      return new Point($point->getX(), $point->getY());
                  },
                  function ($point) {
                      return $point;
                  }
              ))
        ;
  }

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
  	$resolver->setDefaults(array(
        'validation_groups' => $this->groupResolver
    ));
  }

  public function getParent()
  {
      return EasyAdminFormType::class;
  }

}