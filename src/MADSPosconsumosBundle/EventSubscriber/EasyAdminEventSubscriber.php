<?php

namespace MADSPosconsumosBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents as BaseEasyAdminEvents;

/**
 * Class EasyAdminEventSubscriber.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class EasyAdminEventSubscriber implements EventSubscriberInterface
{

	private $security_acl_provider;

	private $security_token_storage;

    private $security_authorization_checker;

    private $mailer;

    private $doctrine_em;

    private $service_container;

    public function __construct($security_acl_provider, $security_token_storage, $security_authorization_checker, $mailer, $doctrine_em, $service_container)
    {
        $this->security_acl_provider = $security_acl_provider;
        $this->security_token_storage = $security_token_storage;
        $this->security_authorization_checker = $security_authorization_checker;
        $this->mailer = $mailer;
        $this->doctrine_em = $doctrine_em;
        $this->service_container = $service_container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            BaseEasyAdminEvents::POST_PERSIST => [['createEntityACL'], ['sendProgramAsociationEmail']],
            BaseEasyAdminEvents::PRE_REMOVE   => ['checkIfGrantedDelete'],
            "mads_posconsumos.pre_edit"       => ['checkIfGrantedEdit'],
        );
    }

	public function createEntityACL(GenericEvent $event)
	{
		$entity = $event->getSubject();
        $event['entity'] = $entity;

        $objectIdentity = ObjectIdentity::fromDomainObject($entity);
        $acl = $this->security_acl_provider->createAcl($objectIdentity);

        $user = $this->security_token_storage->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $this->security_acl_provider->updateAcl($acl);

	}

    public function checkIfGrantedEdit(GenericEvent $event)
    {
        $entity = $event->getSubject();
        $event['entity'] = $entity;

        $user = $this->security_token_storage->getToken()->getUser();
        if (($entity instanceof \MADSPosconsumosBundle\Entity\User) && ($entity->getId() === $user->getId())) {
            return;
        }

        if (false === $this->security_authorization_checker->isGranted('EDIT', $entity) && false === $this->security_authorization_checker->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
    }

    public function checkIfGrantedDelete(GenericEvent $event)
    {
        $entity = $event->getSubject();
        $event['entity'] = $entity;

        if (false === $this->security_authorization_checker->isGranted('DELETE', $entity)) {
            throw new AccessDeniedException();
        }
    }

    public function sendProgramAsociationEmail(GenericEvent $event)
    {
        $entity = $event->getSubject();
        $event['entity'] = $entity;

        $user = $this->security_token_storage->getToken()->getUser();        

        if ($entity instanceof \MADSPosconsumosBundle\Entity\CollectionPoint) {
            $emailTemplateCode = $this->service_container->getParameter('app.emails_codes.notification_user_collectionpoint');
            $htmlEmailTemplate = $this->doctrine_em->getRepository('MADSPosconsumosBundle\Entity\EmailTemplate')->findOneByCode($emailTemplateCode);

            foreach ($entity->getUsers() as $contact) {
                $this->mailer->sendNotificationCollectionPointEmailMessage($contact, $user, $entity, $htmlEmailTemplate->getTemplate(), $htmlEmailTemplate->getSubject());
            }
        } else if ($entity instanceof \MADSPosconsumosBundle\Entity\Campaign) {
            $emailTemplateCode = $this->service_container->getParameter('app.emails_codes.notification_user_campaign');
            $htmlEmailTemplate = $this->doctrine_em->getRepository('MADSPosconsumosBundle\Entity\EmailTemplate')->findOneByCode($emailTemplateCode);

            foreach ($entity->getUsers() as $contact) {
                $this->mailer->sendNotificationCampaignEmailMessage($contact, $user, $entity, $htmlEmailTemplate->getTemplate(), $htmlEmailTemplate->getSubject());
            }
        } else {
            return;
        }        

    }

}