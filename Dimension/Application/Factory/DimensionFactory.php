<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\Factory;

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
        Assert::isMap($dimensionAttributes);

        $instance = static::createDimensionInstance($dimensionClass);
        $dimension = $instance::create($id, $dimensionAttributes);

        Assert::false(
            $dimension->isProjection(),
            sprintf('"%s::create()" is not allowed to return a projection.', \get_class($instance))
        );

        return $dimension;
    }

    public function createProjection(
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionInterface {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $instance = static::createDimensionInstance($dimensionClass);
        $dimension = $instance::createProjection($id, $dimensionAttributes);

        Assert::true(
            $dimension->isProjection(),
            sprintf('"%s::createProjection()" needs to return a projection.', \get_class($instance))
        );

        return $dimension;
    }

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     */
    protected function createDimensionInstance(string $dimensionClass): DimensionInterface
    {
        $reflClass = new \ReflectionClass($dimensionClass);

        /** @var DimensionInterface $instance */
        $instance = $reflClass->newInstanceWithoutConstructor();

        return $instance;
    }
}
