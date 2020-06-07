<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionRemover;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface DimensionRemoverInterface
{
    /**
     * @param class-string<DimensionInterface> $dimensionClass
     */
    public function remove(string $dimensionClass, string $id): void;

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function removeDimension(string $dimensionClass, string $id, array $dimensionAttributes): void;
}
