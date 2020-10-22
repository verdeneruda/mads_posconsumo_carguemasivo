<?php

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

use JavierEguiluz\Bundle\EasyAdminBundle\Form\Type\EasyAdminFormType;

use MADSPosconsumosBundle\Model\Point;
use CrEOF\Spatial\PHP\Types\Geometry\Point as BasePoint;

/**
 * Class ProgramFormType.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class ProgramFormType extends AbstractType
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
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

  public function getParent()
  {
      return EasyAdminFormType::class;
  }

}