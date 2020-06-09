<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\Factory;

use LRH\Bundle\DimensionBundle\Dimension\Application\Util\DimensionInstantiator;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Factory\DimensionFactoryInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use Webmozart\Assert\Assert;

class DimensionFactory implements DimensionFactoryInterface
{
    public function createDimension(
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionInterface {
        $instance = DimensionInstantiator::createInstance($dimensionClass);
        $dimensionAttributes = array_merge(
            array_fill_keys($instance::getAvailableDimensionAttributes(), null),
            $dimensionAttributes
        );

        Assert::isMap($dimensionAttributes);

        $dimension = $instance::create($id, $dimensionAttributes);

        Assert::false(
            $dimension->isProjection(),
            sprintf('"%s::create()" must not return a projection.', $dimensionClass)
        );

        return $dimension;
    }

    public function createProjection(
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionInterface {
        $instance = DimensionInstantiator::createInstance($dimensionClass);
        $dimensionAttributes = array_merge(
            array_fill_keys($instance::getAvailableDimensionAttributes(), null),
            $dimensionAttributes
        );

        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $projection = $instance::createProjection($id, $dimensionAttributes);

        Assert::true(
            $projection->isProjection(),
            sprintf('"%s::createProjection()" needs to return a projection.', $dimensionClass)
        );

        return $projection;
    }
}
