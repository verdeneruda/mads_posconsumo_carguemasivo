<?php 

namespace MADSPosconsumosBundle\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

/**
 * Class AppActionsTypes.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
final class AppActionsTypes extends AbstractEnumType
{

	const INSTALL = 'INSTALL';
	const UNINSTALL = 'UNINSTALL';
	const OPEN = 'OPEN';
	const CLOSE = 'CLOSE';
	const VIEW = 'VIEW';

	protected static $choices = [
		self::INSTALL => 'Instalada',
		self::UNINSTALL => 'Desinstalada',
		self::OPEN => 'Abierta',
		self::CLOSE => 'Cerrada',
		self::VIEW => 'Vista',
	];

}