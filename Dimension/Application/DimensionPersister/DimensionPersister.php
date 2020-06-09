<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionPersister;

use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMerger\DimensionMergerInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Factory\DimensionCollectionFactoryInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use Webmozart\Assert\Assert;

class DimensionPersister implements DimensionPersisterInterface
{
    protected DimensionCollectionFactoryInterface $dimensionCollectionFactory;
    protected DimensionMergerInterface $dimensionMerger;

    public function __construct(
        DimensionCollectionFactoryInterface $dimensionCollectionFactory,
        DimensionMergerInterface $dimensionMerger
    ) {
        $this->dimensionCollectionFactory = $dimensionCollectionFactory;
        $this->dimensionMerger = $dimensionMerger;
    }

    public function persist(DimensionInterface $projection): void
    {
        Assert::true($projection->isProjection(), '"$projection" needs to be a projection.');

        $dimensionCollection = $this->dimensionCollectionFactory->createDimensionCollectionFromExisting(
            \get_class($projection),
            $projection->getId(),
            $projection->getDimensionAttributes()
        );

        $this->dimensionMerger->unmerge($projection, $dimensionCollection);
    }
}
