<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Factory;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionCollectionInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface DimensionCollectionFactoryInterface
{
    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function createDimensionCollection(string $dimensionClass, string $id, array $dimensionAttributes): DimensionCollectionInterface;

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function getDimensionCollection(string $dimensionClass, string $id, array $dimensionAttributes): DimensionCollectionInterface;

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function loadDimensionCollection(string $dimensionClass, string $id, array $dimensionAttributes): DimensionCollectionInterface;
}
