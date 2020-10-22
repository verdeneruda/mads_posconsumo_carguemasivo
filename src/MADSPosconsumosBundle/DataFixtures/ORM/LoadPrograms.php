<?php

namespace MADSPosconsumosBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MADSPosconsumosBundle\Entity\Program;
use CrEOF\Spatial\PHP\Types\Geometry\Point;

/**
 * Class LoadPrograms.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class LoadPrograms extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
    	$program = new Program();
    	$program->setName('Ministerio Ambiente Desarrollo Sostenible');
        $program->setPhone('3323400');
        $program->setEmail('servicioalciudadano@minambiente.gov.co');
        $program->setWebsite('https://www.minambiente.gov.co/');
        $program->setAddressLine1('Cl. 37 #8-40, Bogotá, Colombia');
        $program->setPoint(new Point(-74.0670798, 4.6250205));
        
        $program->setCreatedAt(new \DateTime());

        $this->setReference('MADS', $program);

    	$manager->persist($program);
    	$manager->flush();
    }

    public function getOrder()
    {
        return 40;
    }
}
