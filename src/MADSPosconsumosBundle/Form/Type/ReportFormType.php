<?php

namespace MADSPosconsumosBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use JavierEguiluz\Bundle\EasyAdminBundle\Form\Type\EasyAdminFormType;

use MADSPosconsumosBundle\Entity\User;

/**
 * Class ReportFormType.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class ReportFormType extends AbstractType
{

  private $doctrine_em;
  
  /**
   * {@inheritdoc}
   */  
  public function __construct($doctrine_em)
  {
    $this->doctrine_em = $doctrine_em;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
     $formAddEntityFieldNames = function($entity, $form) use ($builder) {

      $className = "MADSPosconsumosBundle\\Entity\\" . $entity;
      $metadata = $this->doctrine_em->getClassMetadata($className);

      $choices = $this->getEntityFieldNames($entity, $metadata);
      
      $fieldOptions = array(
        'auto_initialize' => false,
        'multiple' => true,
        'choices' => $choices,
        'attr' => array('data-widget' => 'select2')
      );

      $formField = $builder->getFormFactory()->createNamedBuilder('entityFieldNames', ChoiceType::class, null, $fieldOptions);
      $formField->setAttribute('easyadmin_form_group', $builder->get('entityType')->getAttribute('easyadmin_form_group'));

      $form->add($formField->getForm());

    };

    $formAddEntityFilters = function($entity, $form) use ($builder) {

      $className = "MADSPosconsumosBundle\\Entity\\" . $entity;
      $metadata = $this->doctrine_em->getClassMetadata($className);

      $choices = $this->getEntityFieldNames($entity, $metadata);
      
      $fieldOptions = array(
        'auto_initialize' => false,
        'entry_type' => EntityFilterType::class,
        'entry_options'  => array(
          'entity_metadata' => $metadata
        ),
        'delete_empty' => true,
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false,
        'required' => true
      );
      $formField = $builder->getFormFactory()->createNamedBuilder('entityFilters', CollectionType::class, null, $fieldOptions);
      
      $formGroups = $form->getConfig()->getAttribute('easyadmin_form_groups');      
      $formField->setAttribute('easyadmin_form_group', end($formGroups)["fieldName"]);

      $form->add($formField->getForm());

    };

    $builder->addEventListener(
      FormEvents::PRE_SET_DATA,
      function (FormEvent $event) use ($formAddEntityFieldNames, $formAddEntityFilters) {
        
        $report = $event->getData();
        $formAddEntityFieldNames($report->getEntityType(), $event->getForm());
        $formAddEntityFilters($report->getEntityType(), $event->getForm());
        
      }
    );

    $builder->get('entityType')->addEventListener(
      FormEvents::POST_SUBMIT,
      function (FormEvent $event) use ($formAddEntityFieldNames, $formAddEntityFilters) {

        $formAddEntityFieldNames($event->getData(), $event->getForm()->getParent());
        $formAddEntityFilters($event->getData(), $event->getForm()->getParent());

      }
    );
  }

  public function getEntityFieldNames($entity, $metadata)
  {

    $associationsFields = function($alias, $associationMappings) {

      $fields = [];
      foreach ($associationMappings as $associationFieldLvl1 => $associationMappingLvl1) {
        
        $associationMetadataLvl1 = $this->doctrine_em->getClassMetadata($associationMappingLvl1['targetEntity']);
        
        foreach ($associationMetadataLvl1->fieldNames as $fieldNameLvl1) {

          if (!in_array($fieldNameLvl1, User::secureThesePropertiesFromReports())) {
            $fields[$associationFieldLvl1 . "." . $fieldNameLvl1] = $associationFieldLvl1 . "." . $fieldNameLvl1;
          }
        }

        if (count($associationMetadataLvl1->associationMappings) > 0) {

          foreach ($associationMetadataLvl1->associationMappings as $associationFieldLvl2 => $associationMappingLvl2) {


            if ($associationFieldLvl2 !== $alias . "s") {
              
              $associationMetadataLvl2 = $this->doctrine_em->getClassMetadata($associationMappingLvl2['targetEntity']);

              foreach ($associationMetadataLvl2->fieldNames as $fieldNameLvl2) {

                if (!in_array($fieldNameLvl2, User::secureThesePropertiesFromReports())) {
                  $fields[$associationFieldLvl1 . "." . $associationFieldLvl2 . "." . $fieldNameLvl2] = $associationFieldLvl1 . "_" . $associationFieldLvl2 . "." . $fieldNameLvl2;
                }
              }

              if (count($associationMetadataLvl2->associationMappings) > 0) {

                foreach ($associationMetadataLvl2->associationMappings as $associationFieldLvl3 => $associationMappingLvl3) {

                  if ($associationFieldLvl3 !== $alias . "s") {

                    $associationMetadataLvl3 = $this->doctrine_em->getClassMetadata($associationMappingLvl3['targetEntity']);

                    foreach ($associationMetadataLvl3->fieldNames as $fieldNameLvl3) {

                      if (!in_array($fieldNameLvl3, User::secureThesePropertiesFromReports())) {
                        $fields[$associationFieldLvl1 . "." . $associationFieldLvl2 . "." . $associationFieldLvl3 . "." . $fieldNameLvl3] = $associationFieldLvl1 . "_" . $associationFieldLvl2 . "_" . $associationFieldLvl3 . "." . $fieldNameLvl3;
                      }
                    }

                    if (count($associationMetadataLvl3->associationMappings) > 0) {

                      foreach ($associationMetadataLvl3->associationMappings as $associationFieldLvl4 => $associationMappingLvl4) {

                        if ($associationFieldLvl4 !== $alias . "s") {

                          $associationMetadataLvl4 = $this->doctrine_em->getClassMetadata($associationMappingLvl4['targetEntity']);

                          foreach ($associationMetadataLvl4->fieldNames as $fieldNameLvl4) {

                            if (!in_array($fieldNameLvl4, User::secureThesePropertiesFromReports())) {
                              $fields[$associationFieldLvl1 . "." . $associationFieldLvl2 . "." . $associationFieldLvl3 . "." . $associationFieldLvl4 . "." . $fieldNameLvl4] = $associationFieldLvl1 . "_" . $associationFieldLvl2 . "_" . $associationFieldLvl3 . "_" . $associationFieldLvl4 . "." . $fieldNameLvl4;
                            }
                          }

                        }                  

                      }
                      
                    }

                  }

                }
                
              }

            }

          }
          
        }

      }

      return $fields;
    };

    $alias = self::camelCase($entity);
    $fields = [];
    foreach ($metadata->fieldNames as $fieldName) {
      $fields[$fieldName] = $alias . "." . $fieldName;
    }

    return array_merge($fields, $associationsFields($alias, $metadata->associationMappings));
  }  

  private static function camelCase($str, array $noStrip = [])
  {
    // non-alpha and non-numeric characters become spaces
    $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
    $str = trim($str);
    // uppercase the first character of each word
    $str = ucwords($str);
    $str = str_replace(" ", "", $str);
    $str = lcfirst($str);

    return $str;
  }

  public function getParent()
  {
     return EasyAdminFormType::class;
  }

}