<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Factory\DimensionCollectionFactoryInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionCollection;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionCollectionInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Repository\DimensionRepositoryInterface;
use Webmozart\Assert\Assert;

class DimensionCollectionFactory implements DimensionCollectionFactoryInterface
{
    protected DimensionRepositoryInterface $dimensionRepository;

    public function __construct(DimensionRepositoryInterface $dimensionRepository)
    {
        $this->dimensionRepository = $dimensionRepository;
    }

    public function createDimensionCollection(
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionCollectionInterface {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $collection = new ArrayCollection();

        $dimensionAttributeCombinations = $this->getDimensionAttributeCombinations($dimensionAttributes);
        foreach ($dimensionAttributeCombinations as $specificDimensionAttributes) {
            $collection->add(
                $this->createDimension($dimensionClass, $id, $specificDimensionAttributes)
            );
        }

        return $this->createSortedDimensionCollection($collection, $dimensionClass, $id, $dimensionAttributes);
    }

    public function getDimensionCollection(
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionCollectionInterface {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $existingDimensionCollection = $this->loadDimensionCollection($dimensionClass, $id, $dimensionAttributes);
        $collection = new ArrayCollection();

        $dimensionAttributeCombinations = $this->getDimensionAttributeCombinations($dimensionAttributes);
        foreach ($dimensionAttributeCombinations as $specificDimensionAttributes) {
            $specificDimension = $existingDimensionCollection->getSpecificDimension($specificDimensionAttributes);
            if (null === $specificDimension) {
                $specificDimension = $this->createDimension($dimensionClass, $id, $specificDimensionAttributes);
            }

            $collection->add($specificDimension);
        }

        return $this->createSortedDimensionCollection($collection, $dimensionClass, $id, $dimensionAttributes);
    }

    public function loadDimensionCollection(
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionCollectionInterface {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $dimensions = $this->dimensionRepository->findByDimensionAttributes(
            $dimensionClass,
            $id,
            $dimensionAttributes
        );

        return $this->createSortedDimensionCollection(
            new ArrayCollection($dimensions),
            $dimensionClass,
            $id,
            $dimensionAttributes
        );
    }

    /**
     * @param Collection<int, DimensionInterface> $collection
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    protected function createSortedDimensionCollection(
        Collection $collection,
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionCollectionInterface {
        $criteria = Criteria::create()
            ->orderBy(
                array_fill_keys(
                    array_keys($dimensionAttributes),
                    'ASC'
                )
            );

        return new DimensionCollection(
            $collection->matching($criteria)->getValues(),
            $dimensionClass,
            $id,
            $dimensionAttributes
        );
    }

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed|null> $dimensionAttributes
     */
    protected function createDimension(string $dimensionClass, string $id, array $dimensionAttributes): DimensionInterface
    {
        $dimension = $this->dimensionRepository->create($dimensionClass, $id, $dimensionAttributes);
        $this->dimensionRepository->add($dimension);

        return $dimension;
    }

    /**
     * @param array<string, mixed> $dimensionAttributes
     *
     * @return \Generator<array<string, mixed|null>>
     */
    protected function getDimensionAttributeCombinations(array $dimensionAttributes): \Generator
    {
        // Copied from https://stackoverflow.com/a/25611822/7733374
        $comb = function ($m, $a) use (&$comb) {
            if (!$m) {
                yield [];

                return;
            }
            if (!$a) {
                return;
            }
            $h = $a[0];
            $t = \array_slice($a, 1);
            foreach ($comb($m - 1, $t) as $c) {
                yield array_merge([$h], $c);
            }
            foreach ($comb($m, $t) as $c) {
                yield $c;
            }
        };

        $values = array_keys($dimensionAttributes);

        for ($k = 0; $k <= \count($values); ++$k) {
            foreach ($comb($k, $values) as $value) {
                /** @var array<string, mixed> $specificDimensionAttributes */
                $specificDimensionAttributes = array_merge($dimensionAttributes, array_fill_keys($value, null));

                yield $specificDimensionAttributes;
            }
        }
    }
}
