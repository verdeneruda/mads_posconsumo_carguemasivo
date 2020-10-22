<?php 

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use MADSPosconsumosBundle\Entity\CollectionPoint;
use MADSPosconsumosBundle\Enum\CampaignsTypes;
use MADSPosconsumosBundle\Enum\CollectionPointsTypes;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use JavierEguiluz\Bundle\EasyAdminBundle\Form\Type\EasyAdminFormType;

/**
* class CampaignFormType
*
* @author David Alméciga <walmeciga@minambiente.gov.co>
*/
class CampaignFormType extends AbstractType
{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

	 /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $currentUserProgram = $this->tokenStorage->getToken()->getUser()->getProgram();

        $formModifier = function ($form, $campaignType) use ($builder, $currentUserProgram) {

        $collectionPointsOptions = array(
          'label'           => CampaignsTypes::getReadableValue($campaignType),
          'attr'            => array('data-widget' => 'select2'),
          'auto_initialize' => false,
          'multiple'        => true,
          'class'           => CollectionPoint::class,          
          'query_builder'   => function (EntityRepository $er) use ($campaignType, $currentUserProgram) {
            $qb = $er->createQueryBuilder('cp')
                     ->where('cp.collectionPointType = ?1')
                     ->join('cp.users', 'usr')
                     ->andWhere('usr.program = ?2')
                     ->andWhere('cp.enabled = 1')
                     ->orderBy('cp.createdAt')
                     ->setParameter(2, $currentUserProgram->getId());
            
            switch ($campaignType) {
              case CampaignsTypes::COLLECTION_POINTS:
                
                $qb->setParameter(1, CollectionPointsTypes::COLLECTION_POINT);

                break;
              case CampaignsTypes::ROUTE:

                $qb->setParameter(1, CollectionPointsTypes::ROUTE_POINT);
                
                break;
            }

            return $qb;
          }
        );
      	
        $formField = $builder->getFormFactory()->createNamedBuilder('collectionPoints', EntityType::class, null, $collectionPointsOptions);
        $formField->setAttribute('easyadmin_form_group', $builder->get('campaignType')->getAttribute('easyadmin_form_group'));
        
        $form->add($formField->getForm());
        
      };

      $builder->addEventListener(
          FormEvents::PRE_SET_DATA,
          function (FormEvent $event) use ($formModifier) {
            $formModifier($event->getForm(), $event->getData()->getCampaignType());
          }
      );

      $builder->get('campaignType')->addEventListener(
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