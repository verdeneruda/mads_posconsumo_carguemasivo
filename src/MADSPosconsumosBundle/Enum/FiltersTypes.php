<?php 

namespace MADSPosconsumosBundle\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;

/**
 * Class FiltersTypes.
 *
 * @author David Alméciga <walmeciga@minambiente.gov.co>
 */
final class FiltersTypes extends AbstractEnumType
{

	const DATE_FILTER = array(
		'id' => 'date_filter',
		'classname' => Filters\DateFilterType::class,
		'type' => 'datetime',
		'options' => array()
	);

	const RANGE_DATE_FILTER = array(
		'id' => 'range_date_filter',
		'classname' => Filters\DateRangeFilterType::class,
		'type' => 'datetime',
		'options' => array()
	);

	const DATETIME_FILTER = array(
		'id' => 'datetime_filter',
		'classname' => Filters\DateTimeFilterType::class,
		'type' => 'datetime',
		'options' => array()
	);

	const RANGE_DATETIME_FILTER = array(
		'id' => 'range_datetime_filter',
		'classname' => Filters\DateTimeRangeFilterType::class,
		'type' => 'datetime',
		'options' => array()
	);

	const TEXT_FILTER = array(
		'id' => 'text_filter',
		'classname' => Filters\TextFilterType::class,
		'type' => 'string',
		'options' => array(
			'compound' => true
		)
	);

	const BOOLEAN_FILTER = array(
		'id' => 'boolean_filter',
		'classname' => Filters\BooleanFilterType::class,
		'type' => 'boolean',
		'options' => array()
	);

	const NUMBER_FILTER = array(
		'id' => 'number_filter',
		'classname' => Filters\NumberFilterType::class,
		'type' => 'integer',
		'options' => array(
			'compound' => true
		)
	);

	const RANGE_NUMBER_FILTER = array(
		'id' => 'range_number_filter',
		'classname' => Filters\NumberRangeFilterType::class,
		'type' => 'integer',
		'options' => array()
	);

	const ASSOCIATION_FILTER = array(
		'id' => 'association_filter',
		'classname' => Filters\CollectionAdapterFilterType::class,
		'type' => 'association',
		'options' => array()
	);

	const CAMPAIGN_TYPE_FILTER = array(
		'id' => 'campaign_type_filter',
		'classname' => Filters\ChoiceFilterType::class,
		'type' => 'CampaignsTypes',
		'options' => array()
	);

	const COLLECTION_POINT_TYPE_FILTER = array(
		'id' => 'collection_point_type_filter',
		'classname' => Filters\ChoiceFilterType::class,
		'type' => 'CollectionPointsTypes',
		'options' => array()
	);

	protected static $choices = [

		self::DATE_FILTER['id'] => 'Date',

		self::RANGE_DATE_FILTER['id'] => 'Date range',
		
		self::DATETIME_FILTER['id'] => 'Date and time',

		self::RANGE_DATETIME_FILTER['id'] => 'Date and time range',

		self::TEXT_FILTER['id'] => 'Text',

		self::BOOLEAN_FILTER['id'] => 'Boolean',

		self::NUMBER_FILTER['id'] => 'Number',

		self::RANGE_NUMBER_FILTER['id'] => 'Numeric range',

		self::ASSOCIATION_FILTER['id'] => 'Association',

		self::CAMPAIGN_TYPE_FILTER['id'] => 'Campaign type choices',

		self::COLLECTION_POINT_TYPE_FILTER['id'] => 'Collection point type choices',
		
	];

	public static function filterType($type)
	{
		$choices = [];
		switch ($type) {
			case 'datetime':
				$choices[self::DATE_FILTER['id']] = self::$choices[self::DATE_FILTER['id']];
				$choices[self::RANGE_DATE_FILTER['id']] = self::$choices[self::RANGE_DATE_FILTER['id']];
				$choices[self::DATETIME_FILTER['id']] = self::$choices[self::DATETIME_FILTER['id']];
				$choices[self::RANGE_DATETIME_FILTER['id']] = self::$choices[self::RANGE_DATETIME_FILTER['id']];

				break;
			case 'string':
				$choices[self::TEXT_FILTER['id']] = self::$choices[self::TEXT_FILTER['id']];			

				break;
			case 'text':
				$choices[self::TEXT_FILTER['id']] = self::$choices[self::TEXT_FILTER['id']];

				break;
			case 'boolean':
				$choices[self::BOOLEAN_FILTER['id']] = self::$choices[self::BOOLEAN_FILTER['id']];

				break;
			case 'integer':
				$choices[self::NUMBER_FILTER['id']] = self::$choices[self::NUMBER_FILTER['id']];
				$choices[self::RANGE_NUMBER_FILTER['id']] = self::$choices[self::RANGE_NUMBER_FILTER['id']];

				break;
			case 'CampaignsTypes':
				$choices[self::CAMPAIGN_TYPE_FILTER['id']] = self::$choices[self::CAMPAIGN_TYPE_FILTER['id']];

				break;

			case 'CollectionPointsTypes':
				$choices[self::COLLECTION_POINT_TYPE_FILTER['id']] = self::$choices[self::COLLECTION_POINT_TYPE_FILTER['id']];

				break;
			default:
				$choices[self::TEXT_FILTER['id']] = self::$choices[self::TEXT_FILTER['id']];
				break;
		}

		return array_flip($choices);
	}

	private static function allFilters()
	{
		return array(

			self::DATE_FILTER['id'] => self::DATE_FILTER,

			self::RANGE_DATE_FILTER['id'] => self::RANGE_DATE_FILTER,

			self::DATETIME_FILTER['id'] => self::DATETIME_FILTER,

			self::RANGE_DATETIME_FILTER['id'] => self::RANGE_DATETIME_FILTER,

			self::TEXT_FILTER['id'] => self::TEXT_FILTER,

			self::BOOLEAN_FILTER['id'] => self::BOOLEAN_FILTER,

			self::NUMBER_FILTER['id'] => self::NUMBER_FILTER,

			self::RANGE_NUMBER_FILTER['id'] => self::RANGE_NUMBER_FILTER,

			self::ASSOCIATION_FILTER['id'] => self::ASSOCIATION_FILTER,

			self::CAMPAIGN_TYPE_FILTER['id'] => self::CAMPAIGN_TYPE_FILTER,

			self::COLLECTION_POINT_TYPE_FILTER['id'] => self::COLLECTION_POINT_TYPE_FILTER,

		);
	}

	public static function getFilterById($filterId)
	{
		return self::allFilters()[$filterId];
	}
}