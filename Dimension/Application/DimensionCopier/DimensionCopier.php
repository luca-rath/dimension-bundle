<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionCopier;

use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionPersister\DimensionPersisterInterface;
use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionResolver\DimensionResolverInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use Webmozart\Assert\Assert;

class DimensionCopier implements DimensionCopierInterface
{
    protected DimensionResolverInterface $dimensionResolver;
    protected DimensionPersisterInterface $dimensionPersister;

    public function __construct(
        DimensionResolverInterface $dimensionResolver,
        DimensionPersisterInterface $dimensionPersister
    ) {
        $this->dimensionResolver = $dimensionResolver;
        $this->dimensionPersister = $dimensionPersister;
    }

    public function copy(string $dimensionClass, string $id, array $sourceDimensionAttributes, array $targetDimensionAttributes): DimensionInterface
    {
        Assert::isMap($sourceDimensionAttributes);
        Assert::allNotNull($sourceDimensionAttributes);

        $sourceProjection = $this->dimensionResolver->resolve($dimensionClass, $id, $sourceDimensionAttributes);

        return $this->copyProjection($sourceProjection, $targetDimensionAttributes);
    }

    public function copyProjection(DimensionInterface $sourceProjection, array $targetDimensionAttributes): DimensionInterface
    {
        Assert::isMap($targetDimensionAttributes);
        Assert::allNotNull($targetDimensionAttributes);
        Assert::true(
            $sourceProjection->isProjection(),
            '"$sourceProjection" must be a projection.'
        );
        Assert::allNotEq(
            $targetDimensionAttributes,
            $sourceProjection->getDimensionAttributes(),
            '"$targetDimensionAttributes" must not be the same as "$sourceProjection->getDimensionAttributes()"'
        );
        Assert::minCount(
            $targetDimensionAttributes,
            \count($sourceProjection->getDimensionAttributes()),
            'All available dimension attributes must be specified.'
        );

        $targetProjection = clone $sourceProjection;
        $targetProjection->setDimensionAttributes($targetDimensionAttributes);
        $this->dimensionPersister->persist($targetProjection);

        return $targetProjection;
    }
}
