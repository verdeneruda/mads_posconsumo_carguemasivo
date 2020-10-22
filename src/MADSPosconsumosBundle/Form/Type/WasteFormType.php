<?php 

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use JavierEguiluz\Bundle\EasyAdminBundle\Form\Type\EasyAdminFormType;
use MADSPosconsumosBundle\Entity\WasteType;

/**
* class WasteFormType
*
* @author David Alméciga <walmeciga@minambiente.gov.co>
*/
class WasteFormType extends AbstractType
{
	/**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $formModifier = function ($form, WasteType $wasteTypeParent = null) use ($builder) {
      	
        $wasteTypeParentId = null === $wasteTypeParent ? 1 : $wasteTypeParent->getId();

        $wasteTypesOptions = array(
          'auto_initialize' => false,
          'class'         => WasteType::class,
          'query_builder' => function (EntityRepository $er) use ($wasteTypeParentId) {
            return $er->createQueryBuilder('w')
                      ->where('w.parent = ?1')
                      ->andWhere('w.enabled = 1')
                      ->setParameter(1, $wasteTypeParentId)
                      ->orderBy('w.id');
           },
          'multiple' => true,
          'expanded' => true
        );

        $formField = $builder->getFormFactory()->createNamedBuilder('wasteTypes', EntityType::class, null, $wasteTypesOptions);
        $formField->setAttribute('easyadmin_form_group', $builder->get('wasteTypeParent')->getAttribute('easyadmin_form_group'));

        $form->add($formField->getForm());
      };

      $builder->get('wasteTypeParent')->addEventListener(
          FormEvents::PRE_SET_DATA,
          function (FormEvent $event) use ($formModifier) {
            $formModifier($event->getForm()->getParent(), $event->getData());
          }
      );

      $builder->get('wasteTypeParent')->addEventListener(
          FormEvents::POST_SUBMIT,
          function (FormEvent $event) use ($formModifier) {
          	$formModifier($event->getForm()->getParent(), $event->getForm()->getData());
          }
      );
    }

	public function getParent()
  {
      return EasyAdminFormType::class;
  }
	
}