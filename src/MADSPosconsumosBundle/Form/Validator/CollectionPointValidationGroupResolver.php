<?php 

namespace MADSPosconsumosBundle\Form\Validator;

use Symfony\Component\Form\FormInterface;

use MADSPosconsumosBundle\Entity\CollectionPoint;
use MADSPosconsumosBundle\Enum\CollectionPointsTypes;

/**
 * Class CollectionPointValidationGroupResolver
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class CollectionPointValidationGroupResolver
{
    /**
     * @param FormInterface $form
     * @return array
     */
    public function __invoke(FormInterface $form)
    {
        $groups = array();

        $collectionPoint = $form->getData();
        
        switch ($collectionPoint->getCollectionPointType()) {
            
            case CollectionPointsTypes::COLLECTION_POINT:
                
                $groups = array('collection_point_type');

                break;
            case CollectionPointsTypes::ROUTE_POINT:

                $groups = array('route_point_type');
                
                break;
        };

        return $groups;
    }
}
