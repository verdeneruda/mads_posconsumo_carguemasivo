<?php

namespace MADSPosconsumosBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MADSPosconsumosBundle\Entity\User;

/**
 * Class LoadUsers.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class LoadUsers extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
    	$encoder = $this->container->get('security.password_encoder');

        // 'diego escobar' is the admin user allowed to access the MADS Post-Consumer Backend
        $datos = [
           ['name' => 'Diego Escobar',
            'phone' => '(1) 3323400 Ext. 1232',
            'program' => 'MADS',
            'position' => 'Dirección de Asuntos Ambientales Sectorial y Urbana',
            'email' => 'DIEscobar@minambiente.gov.co',
            'roles' => array('ROLE_ADMIN'),
            'pwd' => 'MADSRedPosconsumo']
        ];

        foreach ($datos as $d) {
            $user = new User();
            $user->setFullname($d['name']);
            $user->setPhone($d['phone']);
            $user->setProgram($this->getReference($d['program']));
            $user->setPosition($d['position']);
            $user->setUsername($d['email']);
            $user->setEmail($d['email']);
            $user->setRoles($d['roles']);
            $user->setEnabled(true);
            $user->setPassword($encoder->encodePassword($user, $d['pwd']));
            $user->setCreatedAt(new \DateTime());
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 99;
    }
}
