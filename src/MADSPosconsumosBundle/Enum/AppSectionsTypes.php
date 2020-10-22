<?php 

namespace MADSPosconsumosBundle\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * Class AppSectionsTypes.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
final class AppSectionsTypes extends AbstractEnumType
{

	const HOME = 'HOME';
	const WASTE_TYPE = 'WASTE_TYPE';
	const WASTE_TYPES = 'WASTE_TYPES';
	const COLLECTION_POINT = 'COLLECTION_POINT';
	const CAMPAIGN = 'CAMPAIGN';
	const WASTE = 'WASTE';
	const PROGRAM = 'PROGRAM';
	const CONTACT = 'CONTACT';
	const SEARCH = 'SEARCH';
	const ABOUT = 'ABOUT';

	protected static $choices = [
		self::HOME => 'Inicio',
		self::WASTE_TYPE => 'Tipo Residuo',
		self::WASTE_TYPES => 'Tipos Residuos',
		self::COLLECTION_POINT => 'Punto Recolección',
		self::CAMPAIGN => 'Campaña',
		self::WASTE => 'Residuo',
		self::PROGRAM => 'Programa',
		self::CONTACT => 'Contacto',
		self::SEARCH => 'Búsqueda',
		self::ABOUT => 'Acerca de'
	];

}