<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMover;

use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionCopier\DimensionCopierInterface;
use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionRemover\DimensionRemoverInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use Webmozart\Assert\Assert;

class DimensionMover implements DimensionMoverInterface
{
    protected DimensionCopierInterface $dimensionCopier;
    protected DimensionRemoverInterface $dimensionRemover;

    public function __construct(
        DimensionCopierInterface $dimensionCopier,
        DimensionRemoverInterface $dimensionRemover
    ) {
        $this->dimensionCopier = $dimensionCopier;
        $this->dimensionRemover = $dimensionRemover;
    }

    public function move(DimensionInterface $sourceProjection, array $targetDimensionAttributes): DimensionInterface
    {
        Assert::true(
            $sourceProjection->isProjection(),
            '"$sourceProjection" must be a projection.'
        );

        $targetProjection = $this->dimensionCopier->copy($sourceProjection, $targetDimensionAttributes);

        $this->dimensionRemover->removeDimension(
            \get_class($sourceProjection),
            $sourceProjection->getId(),
            $sourceProjection->getDimensionAttributes()
        );

        return $targetProjection;
    }
}
