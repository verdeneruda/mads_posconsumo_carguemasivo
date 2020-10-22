<?php

namespace MADSPosconsumosBundle\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;

use MADSPosconsumosBundle\Entity\WasteType;

/**
 * Class WasteTypesCollectionDataProvider.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
final class WasteTypesCollectionDataProvider implements CollectionDataProviderInterface
{

	private $doctrine_em;
	
	public function __construct($doctrine_em)
	{
		$this->doctrine_em = $doctrine_em;
	}

	public function getCollection(string $resourceClass, string $operationName = null)
    {
        if (WasteType::class !== $resourceClass) {
            throw new ResourceClassNotSupportedException();
        }

        return $this->doctrine_em->getRepository(WasteType::class)->createQueryBuilder("wt")
                                                                  ->where("wt.enabled = 1")
                                                                  ->andWhere("wt.parent is null")
                                                                  ->orderBy('wt.name')
                                                                  ->getQuery()
                                                                  ->getResult();
    }
}