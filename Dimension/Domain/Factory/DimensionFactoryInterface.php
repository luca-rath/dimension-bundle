<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Factory;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface DimensionFactoryInterface
{
    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed|null> $dimensionAttributes
     */
    public function createDimension(string $dimensionClass, string $id, array $dimensionAttributes): DimensionInterface;

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function createProjection(string $dimensionClass, string $id, array $dimensionAttributes): DimensionInterface;
}
