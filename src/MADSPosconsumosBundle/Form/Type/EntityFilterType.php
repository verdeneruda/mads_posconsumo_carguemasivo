<?php 

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;

use MADSPosconsumosBundle\Enum\FiltersTypes;
use MADSPosconsumosBundle\Enum\CampaignsTypes;
use MADSPosconsumosBundle\Enum\CollectionPointsTypes;

/**
 * class EntityFilterType
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class EntityFilterType extends AbstractType
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    $fieldOptions = array(
      'required' => true,
      'choices' => $options['entity_metadata']->fieldNames,
      'placeholder' => 'Select',
      'empty_data'  => null,
      'attr' => array('class' => 'report_entity_field_names', 'data-widget' => 'select2')
    );

    $builder->add('entity_field_name', ChoiceType::class, $fieldOptions);    

    $formAddFilterTypeField = function($fieldName, $form) use ($builder, $options) {      

      $entityMetadata = $options['entity_metadata'];
      $fieldMapping = $entityMetadata->getFieldMapping($fieldName);

      $choices = FiltersTypes::filterType($fieldMapping['type']);

      $fieldOptions = array(
        'auto_initialize' => false,
        'required' => true,
        'placeholder' => 'Select',
        'empty_data'  => null,
        'choices' => $choices,
        'attr' => array('class' => 'report_filter_type')
      );

      $formField = $builder->getFormFactory()->createNamedBuilder('filter_type', ChoiceType::class, null, $fieldOptions);      
      $formField->setAttribute('easyadmin_form_group', $builder->get('entity_field_name')->getAttribute('easyadmin_form_group'));
      $form->add($formField->getForm());

    };

    $formAddEntityValuesField = function($filterType, $form) use ($builder, $options) {

      $fieldOptions = array(
        'auto_initialize' => false,
        'required' => true,        
        'label' => 'Value(s)'
      );

      $filter = FiltersTypes::getFilterById($filterType);
      switch ($filter['id']) {
        case FiltersTypes::CAMPAIGN_TYPE_FILTER['id']:
          $fieldOptions['choices'] = CampaignsTypes::getChoices();
        break;
        case FiltersTypes::COLLECTION_POINT_TYPE_FILTER['id']:
          $fieldOptions['choices'] = CollectionPointsTypes::getChoices();
        break;
      }

      $formField = $builder->getFormFactory()->createNamedBuilder('entity_field_values', $filter['classname'], null, array_merge($fieldOptions, $filter['options']));
      $formField->setAttribute('easyadmin_form_group', $builder->get('entity_field_name')->getAttribute('easyadmin_form_group'));
      $form->add($formField->getForm());

    };

    $builder->addEventListener(
      FormEvents::PRE_SET_DATA,
      function (FormEvent $event) use ($formAddFilterTypeField, $formAddEntityValuesField) {
        
        $data = $event->getData();
        if ($data) {
          $formAddFilterTypeField($data->getEntityFieldName(), $event->getForm());
          $formAddEntityValuesField($data->getFilterType(), $event->getForm());  
        }        

      }
    );

    $builder->addEventListener(
      FormEvents::SUBMIT,
      function (FormEvent $event) use ($formAddFilterTypeField, $formAddEntityValuesField) {
        
        $data = $event->getData();
        $extraData = $event->getForm()->getExtraData();
        
        if ($data->getEntityFieldName() && !$data->getEntityFieldValues()) {
          $formAddFilterTypeField($data->getEntityFieldName(), $event->getForm());
        }

        if (!empty($extraData) && isset($extraData['filter_type']) && $extraData['filter_type'] !== "") {
          $formAddEntityValuesField($extraData['filter_type'], $event->getForm());
        }

      }
    );

    $builder->addEventListener(
      FormEvents::POST_SUBMIT,
      function (FormEvent $event) use ($formAddFilterTypeField, $formAddEntityValuesField) {
        
        $data = $event->getData();
        $extraData = $event->getForm()->getExtraData();   

        if (!empty($extraData) && isset($extraData['filter_type']) && $extraData['filter_type'] !== "") {
          $data->setFilterType($extraData['filter_type']);
        }

        if (!empty($extraData) &&
            isset($extraData['filter_type']) &&
            $extraData['filter_type'] !== "" &&
            !empty($extraData) &&
            isset($extraData['entity_field_values']) &&
            $extraData['entity_field_values'] !== "") {
          
          $result = null;
          switch ($extraData['filter_type']) {
            case FiltersTypes::DATE_FILTER['id']:
              $date = new \DateTime();
              $date->setDate($extraData['entity_field_values']['year'], $extraData['entity_field_values']['month'], $extraData['entity_field_values']['day']);
              $date->setTime(0,0);
              
              $result = $date;
              break;

            case FiltersTypes::RANGE_DATE_FILTER['id']:
              $leftDate = new \DateTime();
              $leftDate->setDate($extraData['entity_field_values']['left_date']['year'], $extraData['entity_field_values']['left_date']['month'], $extraData['entity_field_values']['left_date']['day']);
              $leftDate->setTime(0,0);

              $rightDate = new \DateTime();
              $rightDate->setDate($extraData['entity_field_values']['right_date']['year'], $extraData['entity_field_values']['right_date']['month'], $extraData['entity_field_values']['right_date']['day']);
              $rightDate->setTime(0,0);

              $result['left_date'] = $leftDate;
              $result['right_date'] = $rightDate;
              break;

            case FiltersTypes::DATETIME_FILTER['id']:
              $dateTime = new \DateTime();
              $dateTime->setDate($extraData['entity_field_values']['date']['year'], $extraData['entity_field_values']['date']['month'], $extraData['entity_field_values']['date']['day']);
              $dateTime->setTime($extraData['entity_field_values']['time']['hour'], $extraData['entity_field_values']['time']['minute']);
              
              $result = $dateTime;
              break;

            case FiltersTypes::RANGE_DATETIME_FILTER['id']:

              $leftDateTime = new \DateTime();
              $leftDateTime->setDate(
                $extraData['entity_field_values']['left_datetime']['date']['year'],
                $extraData['entity_field_values']['left_datetime']['date']['month'],
                $extraData['entity_field_values']['left_datetime']['date']['day']
              );
              $leftDateTime->setTime($extraData['entity_field_values']['left_datetime']['time']['hour'], $extraData['entity_field_values']['left_datetime']['time']['minute']);

              $rightDateTime = new \DateTime();
              $rightDateTime->setDate(
                $extraData['entity_field_values']['right_datetime']['date']['year'],
                $extraData['entity_field_values']['right_datetime']['date']['month'],
                $extraData['entity_field_values']['right_datetime']['date']['day']
              );
              $rightDateTime->setTime($extraData['entity_field_values']['right_datetime']['time']['hour'], $extraData['entity_field_values']['right_datetime']['time']['minute']);

              $result['left_datetime'] = $leftDateTime;
              $result['right_datetime'] = $rightDateTime;
              break;
            
            default:
              $result = $extraData['entity_field_values'];
              break;
          }

          $data->setEntityFieldValues($result);
        }       

      }
    );
  }
  
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
        'data_class' => 'MADSPosconsumosBundle\Entity\EntityFilter',
        'entity_metadata' => null,
        'allow_extra_fields' => true,
        'allow_add' => true,
        'csrf_protection'   => false,
        'validation_groups' => array('filtering')
    ));
  }   

}