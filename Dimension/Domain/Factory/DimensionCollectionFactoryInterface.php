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
    public function createEmptyDimensionCollection(string $dimensionClass, string $id, array $dimensionAttributes): DimensionCollectionInterface;

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function createDimensionCollectionFromExisting(
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionCollectionInterface;

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function loadDimensionCollection(string $dimensionClass, string $id, array $dimensionAttributes): DimensionCollectionInterface;

    /**
     * @param array<string, mixed|null> $dimensionAttributes
     */
    public function getSpecificDimension(DimensionCollectionInterface $dimensionCollection, array $dimensionAttributes): DimensionInterface;

    /**
     * @param array<string, mixed|null> $dimensionAttributes
     */
    public function hasSpecificDimension(DimensionCollectionInterface $dimensionCollection, array $dimensionAttributes): bool;
}
