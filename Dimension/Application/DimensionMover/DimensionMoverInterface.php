<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMover;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface DimensionMoverInterface
{
    /**
     * @param array<string, mixed> $targetDimensionAttributes
     */
    public function move(DimensionInterface $sourceProjection, array $targetDimensionAttributes): DimensionInterface;
}
