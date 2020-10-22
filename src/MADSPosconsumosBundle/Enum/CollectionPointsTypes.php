<?php 

namespace MADSPosconsumosBundle\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * Class CollectionPointsTypes.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
final class CollectionPointsTypes extends AbstractEnumType
{

	const COLLECTION_POINT = 'COLLECTION_POINT';
	const ROUTE_POINT      = 'ROUTE_POINT';

	protected static $choices = [
		self::COLLECTION_POINT => 'Punto de Recolección',
		self::ROUTE_POINT   => 'Punto de Ruta'
	];
}