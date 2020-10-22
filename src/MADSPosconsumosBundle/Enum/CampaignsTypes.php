<?php 

namespace MADSPosconsumosBundle\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * Class CampaignsTypes.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
final class CampaignsTypes extends AbstractEnumType
{

	const COLLECTION_POINTS = 'COLLECTION_POINTS';
	const ROUTE             = 'ROUTE';

	protected static $choices = [
		self::COLLECTION_POINTS => 'Puntos de Recolección',
		self::ROUTE             => 'Ruta'
	];
}