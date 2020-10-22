<?php

namespace MADSPosconsumosBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\UserBundle\Mailer\MailerInterface;

/**
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class MADSTwigSwiftMailer implements MailerInterface
{
    protected $mailer;
    protected $router;
    protected $twig;
    protected $parameters;

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url
        );

        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

    public function sendConfirmationEmailMessageWithTmp(UserInterface $user, $tmp, $htmlBody, $msgSubject)
    {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'tmp' => $tmp,
            'confirmationUrl' => $url,
            'htmlBody' => $htmlBody,
            'msgSubject' => $msgSubject
        );

        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }

    public function sendNotificationCollectionPointEmailMessage($contact, UserInterface $user, $collectionPoint, $htmlBody, $msgSubject)
    {
        $template = 'email/collection_point_notification.html.twig';
        $url = $this->router->generate('easyadmin', array('entity' => 'CollectionPoint', 'action' => 'show', 'id' => $collectionPoint->getId()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'contact' => $contact,
            'collectionPoint' => $collectionPoint,
            'collectionPointRoute' => $url,
            'htmlBody' => $htmlBody,
            'msgSubject' => $msgSubject
        );

        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $contact->getEmail());
    }

    public function sendNotificationCampaignEmailMessage($contact, UserInterface $user, $campaign, $htmlBody, $msgSubject)
    {
        $template = 'email/campaign_notification.html.twig';
        $url = $this->router->generate('easyadmin', array('entity' => 'Campaign', 'action' => 'show', 'id' => $campaign->getId()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'contact' => $contact,
            'campaign' => $campaign,
            'campaignRoute' => $url,
            'htmlBody' => $htmlBody,
            'msgSubject' => $msgSubject
        );

        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $contact->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['resetting'];
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url
        );

        $this->sendMessage($template, $context, $this->parameters['from_email']['resetting'], $user->getEmail());
    }

    /**
     * @param string $templateName
     * @param array  $context
     * @param string $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}
