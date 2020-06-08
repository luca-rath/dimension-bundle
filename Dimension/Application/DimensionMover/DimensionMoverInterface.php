<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMover;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Exception\DimensionNotFoundException;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface DimensionMoverInterface
{
    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $sourceDimensionAttributes
     * @param array<string, mixed> $targetDimensionAttributes
     *
     * @throws DimensionNotFoundException
     */
    public function move(string $dimensionClass, string $id, array $sourceDimensionAttributes, array $targetDimensionAttributes): DimensionInterface;

    /**
     * @param array<string, mixed> $targetDimensionAttributes
     */
    public function moveProjection(DimensionInterface $sourceProjection, array $targetDimensionAttributes): DimensionInterface;
}
