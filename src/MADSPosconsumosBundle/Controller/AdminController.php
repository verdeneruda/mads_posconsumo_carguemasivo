<?php

namespace MADSPosconsumosBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as EasyAdminController;
use JavierEguiluz\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use MADSPosconsumosBundle\Form\Type\DependencyLocationFormType;
use MADSPosconsumosBundle\Form\Type\WasteFormType;
use MADSPosconsumosBundle\Form\Type\CollectionPointFormType;
use MADSPosconsumosBundle\Form\Type\ProgramFormType;
use MADSPosconsumosBundle\Form\Type\CampaignFormType;
use MADSPosconsumosBundle\Form\Type\ReportFormType;

use MADSPosconsumosBundle\Entity\Campaign;
use MADSPosconsumosBundle\Entity\CollectionPoint;
use MADSPosconsumosBundle\Entity\Waste;
use MADSPosconsumosBundle\Entity\WasteType;
use MADSPosconsumosBundle\Entity\User;
use MADSPosconsumosBundle\Entity\Report;
use MADSPosconsumosBundle\Entity\Program;

use MADSPosconsumosBundle\Enum\EntitiesTypes;
use MADSPosconsumosBundle\Enum\FiltersTypes;

use Ddeboer\DataImport\Workflow\StepAggregator;
use Ddeboer\DataImport\Reader\ExcelReader;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\Filter\ValidatorFilter;
use Ddeboer\DataImport\ValueConverter\StringToObjectConverter;
use Ddeboer\DataImport\ValueConverterStep\Step;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

/**
 * Class AdminController.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class AdminController extends EasyAdminController
{
    /**
     * @Route("/", name="easyadmin")
     */
    public function indexAction(Request $request)
    {
        $this->initialize($request);

        if (null === $request->query->get('entity')) {
            return $this->redirectToBackendHomepage();
        }

        $action = $request->query->get('action', 'list');
        if (!$this->isActionAllowed($action)) {
            $errorMessage = sprintf('The requested "%s" action is not allowed for the "%s" entity.', $action, $this->entity['name']);
            throw $this->createAccessDeniedException($errorMessage);
        }

        return $this->executeDynamicMethod($action.'<EntityName>Action');
    }

    public function excelAction()
    {   
        $date = new \DateTime();
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $id = $this->request->query->get('id');
        $report = $em->getRepository("MADSPosconsumosBundle\\Entity\\Report")->find($id);
        
        $query = $em->createQuery($report->getDql());
        $query->setParameters($report->getQueryParameters());

        $result = $query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Red Posconsumo")
           ->setLastModifiedBy($user->getFullname())
           ->setTitle($report->getName());
        
        $activeSheet = $phpExcelObject->setActiveSheetIndex(0);

        // Header
        $activeSheet->fromArray(array_values($report->getEntityFieldNames()), null, 'A1');
        
        // Data
        $rowId = 2;
        foreach ($result as $rowdata) {
            $activeSheet->fromArray($rowdata, null, 'A' . $rowId);
            $rowId++;
        }
        
        $phpExcelObject->getActiveSheet()->setTitle($report->getEntityType());
        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $report->getEntityType() . 's_' . $date->format('Y-m-d') . '_' . $report->getId() . '.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;     
    }

    protected function editAction()
    {

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        $this->dispatch("mads_posconsumos.pre_edit", array('entity' => $entity));

        return parent::editAction();
    }

    public function createNewUserEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function prePersistReportEntity($report)
    {
        $this->saveReportEntity($report);
        
        // dump($qb->getDql());
        // dump($qb->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY));
    }

    public function preUpdateReportEntity($report)
    {
        $this->saveReportEntity($report);
    }

    private function saveReportEntity($report)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $className = "MADSPosconsumosBundle\\Entity\\" . $report->getEntityType();
        $metadata = $em->getClassMetadata($className);
        
        $alias = self::camelCase($report->getEntityType());
        $qb =  $em->createQueryBuilder()
                  ->distinct()
                  ->from($className, $alias);

        foreach ($report->getEntityFieldNames() as $k => $fieldName) {
            $qb->addSelect($fieldName . " AS " . str_replace(".", "_", $fieldName));
        }

        $associationsLvl1 = $metadata->getAssociationMappings();
        if (!empty($associationsLvl1)) {
            
            foreach ($associationsLvl1 as $associationMetadataLvl1) {
                $aliasLvl1 = $associationMetadataLvl1["fieldName"];

                if ($aliasLvl1 !== $alias . "s") {
                    $qb->leftJoin($alias . "." . $associationMetadataLvl1["fieldName"], $aliasLvl1);

                    $associationsLvl2 = $em->getClassMetadata($associationMetadataLvl1["targetEntity"])->getAssociationMappings();
                    if (!empty($associationsLvl2)) {
                        
                        foreach ($associationsLvl2 as $associationMetadataLvl2) {
                            $aliasLvl2 = $aliasLvl1 . "_" . $associationMetadataLvl2["fieldName"];

                            if ($associationMetadataLvl2["fieldName"] !== $alias . "s") {
                                $qb->leftJoin($aliasLvl1 . "." . $associationMetadataLvl2["fieldName"], $aliasLvl2);

                                $associationsLvl3 = $em->getClassMetadata($associationMetadataLvl2["targetEntity"])->getAssociationMappings();
                                if (!empty($associationsLvl3)) {
                                    
                                    foreach ($associationsLvl3 as $associationMetadataLvl3) {
                                        $aliasLvl3 = $aliasLvl2 . "_" . $associationMetadataLvl3["fieldName"];
                                        
                                        if ($associationMetadataLvl3["fieldName"] !== $alias . "s") {
                                            $qb->leftJoin($aliasLvl2 . "." . $associationMetadataLvl3["fieldName"], $aliasLvl3);

                                            $associationsLvl4 = $em->getClassMetadata($associationMetadataLvl3["targetEntity"])->getAssociationMappings();
                                            if (!empty($associationsLvl4)) {
                                                
                                                foreach ($associationsLvl4 as $associationMetadataLvl4) {
                                                    $aliasLvl4 = $aliasLvl3 . "_" . $associationMetadataLvl4["fieldName"];

                                                    if ($associationMetadataLvl4["fieldName"] !== $alias . "s") {
                                                        $qb->leftJoin($aliasLvl3 . "." . $associationMetadataLvl4["fieldName"], $aliasLvl4);
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

        $formBuilder = $this->createFormBuilder();
        foreach ($report->getEntityFilters() as $entityFilter) {            
            $filter = FiltersTypes::getFilterById($entityFilter->getFilterType());            
            $formBuilder->add($entityFilter->getEntityFieldName(), $filter['classname'], $filter['options']);
            $formBuilder->get($entityFilter->getEntityFieldName())->setData($entityFilter->getEntityFieldValues());
        }

        $form = $formBuilder->getForm();
        $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $qb, $alias);

        if (in_array("ROLE_USER", $user->getRoles())) {
            
            switch ($report->getEntityType()) {
            
                case EntitiesTypes::CAMPAIGNS:
                case EntitiesTypes::COLLECTION_POINTS:
                case EntitiesTypes::PROGRAMS:            
                    $qb->andWhere('users' . '_program' . '.id = :program_id')
                       ->setParameter("program_id", $user->getProgram()->getId());
                    break;
                case EntitiesTypes::WASTE:
                    $qb->andWhere('program.id = :program_id')
                       ->setParameter("program_id", $user->getProgram()->getId());
                    break;
            }
        }
                
        $report->setQueryParameters($qb->getParameters());        
        $report->setDql($qb->getDql());
        $report->setProgram($user->getProgram());
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

    public function prePersistUserEntity($user)
    {
    	$encoder = $this->get('security.password_encoder');
    	$slugify = $this->get('slugify');
        $dispatcher = $this->get('event_dispatcher');
        $mailer = $this->get('fos_user.mailer');
        $tokenGenerator = $this->get('fos_user.util.token_generator');
        $em = $this->getDoctrine()->getManager();

    	$tmp = $slugify->slugify($user->getFullname());
    	$user->setUsername($user->getEmail());
    	$user->setPassword($encoder->encodePassword($user, $tmp));

        
        //Email Template
        $emailTemplateCode = $this->container->getParameter('app.emails_codes.confirmation');
        $htmlEmailTemplate = $em->getRepository('MADSPosconsumosBundle\Entity\EmailTemplate')->findByCode($emailTemplateCode);

        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
        if ($confirmationEnabled) {
            $user->setEnabled(false);
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }
            $mailer->sendConfirmationEmailMessageWithTmp($user, $tmp, $htmlEmailTemplate[0]->getTemplate(), $htmlEmailTemplate[0]->getSubject());
        }

        $this->get('fos_user.user_manager')->updateUser($user, false);
        $em->flush();
    }

    public function preUpdateUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        $this->getDoctrine()->getManager()->flush();
    }

    public function createReportEntityFormBuilder($entity, $view)
    {
        $formOptions = $this->getEntityFormOptions($entity, $view);
        return $this->get('form.factory')->createNamedBuilder('report', ReportFormType::class, $entity, $formOptions);
    }

    public function createWasteEntityFormBuilder($entity, $view)
    {
        $formOptions = $this->getEntityFormOptions($entity, $view);
        return $this->get('form.factory')->createNamedBuilder('waste', WasteFormType::class, $entity, $formOptions);
    }

    public function createCollectionPointEntityFormBuilder($entity, $view)
    {
        $formOptions = $this->getEntityFormOptions($entity, $view);
        return $this->get('form.factory')->createNamedBuilder('collectionpoint', CollectionPointFormType::class, $entity, $formOptions);
    }

    public function createProgramEntityFormBuilder($entity, $view)
    {
        $formOptions = $this->getEntityFormOptions($entity, $view);
        return $this->get('form.factory')->createNamedBuilder('program', ProgramFormType::class, $entity, $formOptions);
    }

    public function createCampaignEntityFormBuilder($entity, $view)
    {
        $formOptions = $this->getEntityFormOptions($entity, $view);
        return $this->get('form.factory')->createNamedBuilder('campaign', CampaignFormType::class, $entity, $formOptions);
    }

    /**
     * @Route("/collectionpoint/import", name="mads_posconsumos_collection_point_import")
     */
    public function collectionPointImportAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('attachment', FileType::class, array('label' => 'Archivo Excel', 'required' => true, 'constraints' => array(
                new NotBlank(),
                new File(array(
                    'mimeTypes' => array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
                    'mimeTypesMessage' => 'Por favor suba un archivo Excel válido.',
                ))
            )))
            ->add('headers_row', IntegerType::class, array('label' => 'Número de fila con los Encabezados', 'required' => true, 'data' => 1, 'constraints' => array( new NotBlank() )))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $data = $form->getData();
            $file = $data['attachment'];
            $em = $this->getDoctrine()->getManager();

            $file->move(
                $this->container->getParameter('app.path.imports_directory'),
                $file->getClientOriginalName()
            );

            $fileObj = new \SplFileObject($this->container->getParameter('app.path.imports_directory') . '/' . $file->getClientOriginalName());
            $excelReader = new ExcelReader($fileObj, 1);
            
            $workflow = new StepAggregator($excelReader);

            $doctrineWriter = new DoctrineWriter($em, CollectionPoint::class);
            $workflow->addWriter($doctrineWriter);

            $stringToPoint = new \MADSPosconsumosBundle\Model\ValueConverter\StringToPointValueConverter();
            $workflow->addValueConverter('point', $stringToPoint);

            $workflow->process();
        }

        return $this->render('custom/import.html.twig', array(
            'entity' => 'CollectionPoint',
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/changelog", name="mads_posconsumos_changelog")
     */
    public function changelogAction()
    {
        return $this->render('custom/changelog.html.twig', array('entity' => ''));
    }

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null)
    {
        $user = $this->getUser();
        
        /** @var EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var DoctrineQueryBuilder */
        $queryBuilder = $em->createQueryBuilder()
            ->select('entity')
            ->from($entityClass, 'entity');

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
       
            switch ($entityClass) {
                case Campaign::class:
                case CollectionPoint::class:
                    $queryBuilder->join('entity.users', 'users')
                                 ->join('users.program', 'program')
                                 ->where('program.id = ?1')
                                 ->setParameter(1, $user->getProgram()->getId());
                    break;
                case Report::class:
                case Waste::class:
                case User::class:
                    $queryBuilder->where('entity.program = ?1')
                                 ->setParameter(1, $user->getProgram()->getId());
                    break;
            }

        }

        if (null !== $sortField) {
            $queryBuilder->orderBy('entity.'.$sortField, $sortDirection);
        }

        return $queryBuilder;
    }

    protected function profileUserAction()
    {
        $user = $this->getUser();
        $easyadmin = $this->request->attributes->get('easyadmin');

        $entity = $this->getDoctrine()->getManager()->getRepository(User::class)->find($user->getId());
        $fields = $this->entity['show']['fields'];

        return $this->render($this->entity['profile']['template'], array(
             'entity' => $entity,
             'fields' => $fields
        ));
    }

    protected function resetpwdUserAction()
    {
        $providerKey = $this->container->getParameter('fos_user.firewall_name');

        $this->get('security.token_storage')->setToken(null);
        $this->request->getSession()->invalidate();

        return $this->redirectToRoute('fos_user_resetting_request');
    }

    private function executeDynamicMethod($methodNamePattern, array $arguments = array())
    {
        $methodName = str_replace('<EntityName>', $this->entity['name'], $methodNamePattern);
        if (!is_callable(array($this, $methodName))) {
            $methodName = str_replace('<EntityName>', '', $methodNamePattern);
        }

        return call_user_func_array(array($this, $methodName), $arguments);
    }

    private function redirectToBackendHomepage()
    {
        $homepageConfig = $this->config['homepage'];

        $url = isset($homepageConfig['url'])
            ? $homepageConfig['url']
            : $this->get('router')->generate($homepageConfig['route'], $homepageConfig['params']);

        return $this->redirect($url);
    }

    protected function isActionAllowed($actionName)
    {
        return false === in_array($actionName, $this->entity['disabled_actions'], true) && $this->get('security.authorization_checker')->isGranted($this->entity['role']);
    }



    /**
     * @Route("/massive.php", name="mads_posconsumos_massive")
     */

    public function massiveAction()
    {
        return $this->render('easy_admin/CollectionPoint/massive.php');
    }

    /**
     * @Route("/load_massive.php", name="mads_posconsumos_load_massive")
     */

    public function load_massiveAction()
    {
        return $this->redirect('easy_admin/CollectionPoint/load_massive.php');
    }

    

    

}
