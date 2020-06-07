<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionResolver;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Exception\DimensionNotFoundException;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface DimensionResolverInterface
{
    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     *
     * @throws DimensionNotFoundException
     */
    public function resolve(string $dimensionClass, string $id, array $dimensionAttributes): DimensionInterface;
}
