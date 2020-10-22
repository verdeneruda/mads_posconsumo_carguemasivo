<?php 

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

/**
* class EntityParentType
*
* @author David Alméciga <walmeciga@minambiente.gov.co>
*/
class EntityParentType extends AbstractType
{
	
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                          ->where('c.parent is null')
                          ->orderBy('c.id');
            }
        ));

    	parent::configureOptions($resolver);
    }

	public function getParent()
    {
        return EntityType::class;
    }
	
}