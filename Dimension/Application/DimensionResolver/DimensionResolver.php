<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionResolver;

use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMerger\DimensionMergerInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Exception\DimensionNotFoundException;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Factory\DimensionCollectionFactoryInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Factory\DimensionFactoryInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use Webmozart\Assert\Assert;

class DimensionResolver implements DimensionResolverInterface
{
    protected DimensionCollectionFactoryInterface $dimensionCollectionFactory;
    protected DimensionFactoryInterface $dimensionFactory;
    protected DimensionMergerInterface $dimensionMerger;

    public function __construct(
        DimensionCollectionFactoryInterface $dimensionCollectionFactory,
        DimensionFactoryInterface $dimensionFactory,
        DimensionMergerInterface $dimensionMerger
    ) {
        $this->dimensionCollectionFactory = $dimensionCollectionFactory;
        $this->dimensionFactory = $dimensionFactory;
        $this->dimensionMerger = $dimensionMerger;
    }

    public function resolve(string $dimensionClass, string $id, array $dimensionAttributes): DimensionInterface
    {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $dimensionCollection = $this->dimensionCollectionFactory->loadDimensionCollection(
            $dimensionClass,
            $id,
            $dimensionAttributes
        );

        if (null === $dimensionCollection->getSpecificDimension($dimensionAttributes)) {
            throw new DimensionNotFoundException($dimensionClass, $id, $dimensionAttributes);
        }

        $projection = $this->dimensionFactory->createProjection($dimensionClass, $id, $dimensionAttributes);
        $this->dimensionMerger->merge($dimensionCollection, $projection);

        return $projection;
    }
}
