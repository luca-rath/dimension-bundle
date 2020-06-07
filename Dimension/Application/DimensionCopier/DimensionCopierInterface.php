<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionCopier;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface DimensionCopierInterface
{
    /**
     * @param array<string, mixed> $targetDimensionAttributes
     */
    public function copy(DimensionInterface $sourceProjection, array $targetDimensionAttributes): DimensionInterface;
}
