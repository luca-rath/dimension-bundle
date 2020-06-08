<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionCopier;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Exception\DimensionNotFoundException;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface DimensionCopierInterface
{
    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $sourceDimensionAttributes
     * @param array<string, mixed> $targetDimensionAttributes
     *
     * @throws DimensionNotFoundException
     */
    public function copy(string $dimensionClass, string $id, array $sourceDimensionAttributes, array $targetDimensionAttributes): DimensionInterface;

    /**
     * @param array<string, mixed> $targetDimensionAttributes
     */
    public function copyProjection(DimensionInterface $sourceProjection, array $targetDimensionAttributes): DimensionInterface;
}
