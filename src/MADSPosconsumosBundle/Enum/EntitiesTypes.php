<?php 

namespace MADSPosconsumosBundle\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * Class EntitiesTypes.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
final class EntitiesTypes extends AbstractEnumType
{

	const CAMPAIGNS = 'Campaign';

	const COLLECTION_POINTS = 'CollectionPoint';

	const PROGRAMS = 'Program';

	const WASTE = 'Waste';

	protected static $choices = [
		self::CAMPAIGNS => 'Campañas',
		self::COLLECTION_POINTS => 'Puntos de recolección',
		self::PROGRAMS => 'Programas',
		self::WASTE => 'Residuos',
	];
}