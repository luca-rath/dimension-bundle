<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Repository;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface DimensionRepositoryInterface
{
    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed|null> $dimensionAttributes
     */
    public function create(string $dimensionClass, string $id, array $dimensionAttributes): DimensionInterface;

    public function add(DimensionInterface $dimension): void;

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     *
     * @return DimensionInterface[]
     */
    public function findByDimensionAttributes(string $dimensionClass, string $id, array $dimensionAttributes): array;

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed|null> $dimensionAttributes
     */
    public function findOneByDimensionAttributes(string $dimensionClass, string $id, array $dimensionAttributes): ?DimensionInterface;

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     */
    public function remove(string $dimensionClass, string $id): void;

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function removeByDimensionAttributes(string $dimensionClass, string $id, array $dimensionAttributes): void;
}
