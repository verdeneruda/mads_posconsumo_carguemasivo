<?php

namespace MADSPosconsumosBundle\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Api\IriConverterInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class GeoPointFilter extends AbstractFilter
{

    const PARAMETER_SOUTH  = 'south';
    const PARAMETER_WEST   = 'west';
    const PARAMETER_NORTH  = 'north';
    const PARAMETER_EAST   = 'east';

    protected $iriConverter;
    protected $propertyAccessor;

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack, IriConverterInterface $iriConverter, PropertyAccessorInterface $propertyAccessor = null, LoggerInterface $logger = null, array $properties = null)
    {
        parent::__construct($managerRegistry, $requestStack, $logger, $properties);

        $this->iriConverter = $iriConverter;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(string $resourceClass) : array
    {
        $description = [];

        $properties = $this->properties;
        foreach ($properties as $property => $param) {

            if (!$this->isPropertyMapped($property, $resourceClass)) {
                continue;
            }

            $description += $this->getFilterDescription($property, self::PARAMETER_SOUTH);
            $description += $this->getFilterDescription($property, self::PARAMETER_WEST);
            $description += $this->getFilterDescription($property, self::PARAMETER_NORTH);
            $description += $this->getFilterDescription($property, self::PARAMETER_EAST);
            
        }
        return $description;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterProperty(string $property, $values, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (!is_array($values) ||
            !$this->isPropertyEnabled($property) ||
            !$this->isPropertyMapped($property, $resourceClass) ||
            !isset($values[self::PARAMETER_WEST]) ||
            !isset($values[self::PARAMETER_SOUTH]) ||
            !isset($values[self::PARAMETER_EAST]) ||
            !isset($values[self::PARAMETER_NORTH])) {
            return;
        }

        $queryBuilder->andWhere(sprintf("ST_Contains(ST_MakeEnvelope(Point(:west, :south), Point(:east, :north)), %s.%s) = 1", "o", $property));
        
        $queryBuilder->setParameter(self::PARAMETER_WEST, $values[self::PARAMETER_WEST]);
        $queryBuilder->setParameter(self::PARAMETER_SOUTH, $values[self::PARAMETER_SOUTH]);
        $queryBuilder->setParameter(self::PARAMETER_EAST, $values[self::PARAMETER_EAST]);
        $queryBuilder->setParameter(self::PARAMETER_NORTH, $values[self::PARAMETER_NORTH]);
    }

    /**
     * Gets filter description.
     *
     * @param string $property
     * @param string $period
     *
     * @return array
     */
    protected function getFilterDescription(string $property, string $parameter) : array
    {
        return [
            sprintf('%s[%s]', $property, $parameter) => [
                'property' => $property,
                'type' => 'float',
                'required' => false,
            ],
        ];
    }
}