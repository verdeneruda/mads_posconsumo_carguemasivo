<?php 

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use MADSPosconsumosBundle\Entity\WasteType;

use Doctrine\ORM\EntityRepository;

/**
* class WasteTypeParentType
*
* @author David Alméciga <walmeciga@minambiente.gov.co>
*/
class WasteTypeParentType extends AbstractType
{

  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
    
    $resolver->setNormalizer('query_builder', function (Options $options, $configs) {
    
      return $options['em']->getRepository($options['class'])->createQueryBuilder('w')
                                                    ->where('w.parent is null')
                                                    ->andWhere('w.enabled = 1')
                                                    ->orderBy('w.id');

    });
  }

  public function getParent()
  {
      return EntityType::class;
  }
	
}