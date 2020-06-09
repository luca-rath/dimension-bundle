<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionResolver;

use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMerger\DimensionMergerInterface;
use LRH\Bundle\DimensionBundle\Dimension\Application\Util\DimensionInstantiator;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Exception\DimensionNotFoundException;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Factory\DimensionCollectionFactoryInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Factory\DimensionFactoryInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionCollectionInterface;
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
        $dimensionCollection = $this->loadDimensionCollection($dimensionClass, $id, $dimensionAttributes);

        if (!$this->dimensionCollectionFactory->hasSpecificDimension($dimensionCollection, $dimensionAttributes)) {
            throw new DimensionNotFoundException($dimensionClass, $id, $dimensionAttributes);
        }

        return $this->mergeDimensionCollection($dimensionCollection);
    }

    public function resolvePartial(string $dimensionClass, string $id, array $dimensionAttributes): DimensionInterface
    {
        $dimensionCollection = $this->loadDimensionCollection($dimensionClass, $id, $dimensionAttributes);

        return $this->mergeDimensionCollection($dimensionCollection);
    }

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    protected function loadDimensionCollection(string $dimensionClass, string $id, array $dimensionAttributes): DimensionCollectionInterface
    {
        $instance = DimensionInstantiator::createInstance($dimensionClass);
        $dimensionAttributes = array_merge(
            array_fill_keys($instance::getAvailableDimensionAttributes(), null),
            $dimensionAttributes
        );

        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        return $this->dimensionCollectionFactory->loadDimensionCollection(
            $dimensionClass,
            $id,
            $dimensionAttributes
        );
    }

    protected function mergeDimensionCollection(DimensionCollectionInterface $dimensionCollection): DimensionInterface
    {
        $projection = $this->dimensionFactory->createProjection(
            $dimensionCollection->getDimensionClass(),
            $dimensionCollection->getId(),
            $dimensionCollection->getDimensionAttributes()
        );

        $this->dimensionMerger->merge($dimensionCollection, $projection);

        return $projection;
    }
}
