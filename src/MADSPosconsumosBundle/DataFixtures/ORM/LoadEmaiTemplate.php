<?php

namespace MADSPosconsumosBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MADSPosconsumosBundle\Entity\EmailTemplate;

/**
 * Class LoadEmailTemplate.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class LoadEmailTemplate extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
    	$datos = [
    		['code' => 'email-bienvenida', 'subject' => 'Bienvenido %fullname%', 'template' => '<h2>Bienvenidos a la aplicaci&oacute;n <strong><em>Red Posconsumo</em></strong></h2><p>Desarrollada por el Ministerio de Ambiente y Desarrollo Sostenible-MADS, que tiene como objeto principal el de dar a conocer al p&uacute;blico en general sobre los puntos, campa&ntilde;as y rutas de recolecci&oacute;n, que ustedes como responsables de los <em>Programas posconsumo</em> y <em>Sistemas de Recolecci&oacute;n Selectiva</em> han implementado o tienen contemplado implementar con el fin de permitir la devoluci&oacute;n de los residuos posconsumo por parte de los usuarios.</p><p>En general la aplicaci&oacute;n les permitir&aacute;:</p><ol>   <li><em>Administrar la informaci&oacute;n sobre su programa (puntos, campa&ntilde;as, rutas de recolecci&oacute;n, etc)&nbsp;&nbsp;</em></li>   <li><em>Georeferenciar puntos y rutas de recolecci&oacute;n para ser visualizados por los usuarios de la aplicaci&oacute;n.</em></li>   <li><em>Capacidad de informar sobre las fechas, lugares y tipo de residuos a recolectar en sus campa&ntilde;as de recolecci&oacute;n.</em></li> <li><em>Exportar datos de la informaci&oacute;n presentada al usuario.</em></li>    <li><em>Obtenci&oacute;n de estad&iacute;sticas relacionadas con retroalimentaci&oacute;n por parte del usuario.</em></li></ol><p>Por favor tenga en cuenta las siguientes observaciones al hacer uso de la aplicaci&oacute;n:</p><ol>  <li>La informaci&oacute;n presentada por ustedes en la aplicaci&oacute;n ser&aacute; visible por el p&uacute;blico en general por lo que se recomienda mantenerla lo m&aacute;s actualizada posible.</li>   <li>La responsabilidad por la informaci&oacute;n subida en la aplicaci&oacute;n <em>&ldquo;Red Posconsumo&rdquo;</em> es &uacute;nica y exclusivamente del <em>Programa posconsumo</em> o <em>Sistemas de Recolecci&oacute;n Selectiva</em>.</li>   <li>La informaci&oacute;n presentada en aplicaci&oacute;n <em>&ldquo;Red Posconsumo&rdquo;</em>&nbsp; no excluye ninguna de las obligaciones establecidas en las Resoluciones que reglamentan el posconsumo.</li></ol><p>En caso de estar de acuerdo con las observaciones anteriores, podr&aacute; acceder a la plataforma de administraci&oacute;n de la aplicaci&oacute;n con el siguiente usuario y contrase&ntilde;a:</p><p>&nbsp;</p><p>Usuario: <strong>%username%</strong></p><p>Contrase&ntilde;a: <strong>%password%</strong></p><p>Para acceder a la aplicaci&oacute;n ingrese en el siguiente link:</p><p><strong><a href="%confirmationUrl%">%confirmationUrl%</a></strong></p><p>&nbsp;</p><p><strong>Mensaje de Advertencia:</strong></p><p>La informaci&oacute;n presentada en esta aplicaci&oacute;n ha sido cargada por los responsables de los Programas posconsumo y Sistemas de Recolecci&oacute;n Selectiva y podr&aacute; estar sujeta a verificaci&oacute;n por parte de la Autoridad Nacional de Licencias Ambientales.</p><p>Este mensaje ha sido autogenerado por el sistema Red Posconsumo, por favor NO responder al remitente.</p>'],
            ['code' => 'email-notificacion-collectionpoint-usuario', 'subject' => 'Notificación, Red Posconsumo', 'template' => '<p>Hola <strong>%contactFullName%</strong>,</p><p>Has sido vinculado al punto de recolecci&oacute;n <strong>%collectionPointName%.</strong></p><div>Puede ver los detalles de esta informaci&oacute;n en el siguiente enlace:</div><p><a href="%collectionPointUrl%">%collectionPointUrl%</a></p><hr /><div>Atentamente,</div><div><strong>%userFullname%</strong></div><div>%userEmail%</div><div>%userProgram%</div><p>Este mensaje ha sido autogenerado por el sistema Red Posconsumo, por favor NO responder al remitente.</p>'],
            ['code' => 'email-notificacion-campaign-usuario', 'subject' => 'Notificación, Red Posconsumo', 'template' => '<p>Hola <strong>%contactFullName%</strong>,</p><p>Has sido vinculado a la campaña <strong>%campaignName%.</strong></p><div>Puede ver los detalles de esta informaci&oacute;n en el siguiente enlace:</div><p><a href="%campaignUrl%">%campaignUrl%</a></p><hr /><div>Atentamente,</div><div><strong>%userFullname%</strong></div><div>%userEmail%</div><div>%userProgram%</div><p>Este mensaje ha sido autogenerado por el sistema Red Posconsumo, por favor NO responder al remitente.</p>'],
    	];

    	foreach ($datos as $d) {
    		$emailTemplate = new EmailTemplate();
    		$emailTemplate->setSubject($d['subject']);
            $emailTemplate->setCode($d['code']);
            $emailTemplate->setTemplate($d['template']);
            $emailTemplate->setCreatedAt(new \DateTime());
    		$manager->persist($emailTemplate);
    	}

    	$manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
