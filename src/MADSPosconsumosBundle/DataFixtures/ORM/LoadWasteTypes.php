<?php

namespace MADSPosconsumosBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MADSPosconsumosBundle\Entity\WasteType;

/**
 * Class LoadWasteTypes.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
class LoadWasteTypes extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
    	$tiposResiduos = [
            ['ref' => 1, 'name' => 'Plaguicidas', 'classname' => 'ic_plaguicidas'],
            ['ref' => 2, 'name' => 'Medicamentos', 'classname' => 'ic_medicamentos'],
            ['ref' => 3, 'name' => 'Baterías plomo ácido', 'classname' => 'ic_bateriasplomo'],
            ['ref' => 4, 'name' => 'Llantas ', 'classname' => 'ic_llantas'],
            ['ref' => 5, 'name' => 'Pilas', 'classname' => 'ic_pilas'],
            ['ref' => 6, 'name' => 'Bombillas', 'classname' => 'ic_bombillas'],
            ['ref' => 7, 'name' => 'Computadores', 'classname' => 'ic_computadores'],
    	];

        $subTiposResiduos = [
            ['ref_cat' => 1, 'name' => 'Uso agrícola'],
            ['ref_cat' => 1, 'name' => 'Uso veterinario'],
            ['ref_cat' => 1, 'name' => 'Uso doméstico'],
            ['ref_cat' => 2, 'name' => 'Uso humano'],
            ['ref_cat' => 2, 'name' => 'Uso veterinario'],
            ['ref_cat' => 3, 'name' => 'Para carros'],
            ['ref_cat' => 3, 'name' => 'Para motos'],
            ['ref_cat' => 4, 'name' => 'Para carros'],
            ['ref_cat' => 4, 'name' => 'Para camionetas'],
            ['ref_cat' => 4, 'name' => 'Para camiones'],
            ['ref_cat' => 4, 'name' => 'Para buses'],
            ['ref_cat' => 4, 'name' => 'Para vehículos de carga'],
            ['ref_cat' => 5, 'name' => 'Cualquier tipo de pilas'],
            ['ref_cat' => 5, 'name' => 'Pilas botón'],
            ['ref_cat' => 5, 'name' => 'Pilas para computadores portátiles'],
            ['ref_cat' => 5, 'name' => 'Pilas para teléfono celular'],
            ['ref_cat' => 6, 'name' => 'Cualquier tipo de bombilla fluorescente'],
            ['ref_cat' => 6, 'name' => 'Bombillas fluorescentes compactas'],
            ['ref_cat' => 7, 'name' => 'Cualquier computador o periférico'],
            ['ref_cat' => 7, 'name' => 'Computadores portátiles'],
            ['ref_cat' => 7, 'name' => 'Impresoras'],
        ];

    	foreach ($tiposResiduos as $c) {
    		$tipoResiduo = new WasteType();
    		$tipoResiduo->setName($c['name']);
            $tipoResiduo->setClassname($c['classname']);
            $tipoResiduo->setCreatedAt(new \DateTime());

            $this->addReference('tipo_residuo_' . $c['ref'], $tipoResiduo);
    		$manager->persist($tipoResiduo);
    	}

    	$manager->flush();

        foreach ($subTiposResiduos as $s) {
            $subTipoResiduo = new WasteType();
            $subTipoResiduo->setName($s['name']);
            $subTipoResiduo->setCreatedAt(new \DateTime());
            $subTipoResiduo->setParent($this->getReference('tipo_residuo_' . $s['ref_cat']));
            
            $manager->persist($subTipoResiduo);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 30;
    }
}