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

    public function createEmptyDimensionCollection(
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionCollectionInterface {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $dimensions = [
            $this->createDimension($dimensionClass, $id, $dimensionAttributes),
        ];

        return $this->createSortedDimensionCollection($dimensions, $dimensionClass, $id, $dimensionAttributes);
    }

    public function createDimensionCollectionFromExisting(
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionCollectionInterface {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $existingDimensionCollection = $this->loadDimensionCollection(
            $dimensionClass,
            $id,
            $dimensionAttributes
        );

        $collection = $existingDimensionCollection->getDimensions();
        if (!$this->hasSpecificDimension($existingDimensionCollection, $dimensionAttributes)) {
            $collection->add(
                $this->createDimension($dimensionClass, $id, $dimensionAttributes)
            );
        }

        return $this->createSortedDimensionCollection(
            $collection->getValues(),
            $dimensionClass,
            $id,
            $dimensionAttributes
        );
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
            $dimensions,
            $dimensionClass,
            $id,
            $dimensionAttributes
        );
    }

    public function hasSpecificDimension(DimensionCollectionInterface $dimensionCollection, array $dimensionAttributes): bool
    {
        return null !== $this->getSpecificDimensionOrNull($dimensionCollection, $dimensionAttributes);
    }

    public function getSpecificDimension(DimensionCollectionInterface $dimensionCollection, array $dimensionAttributes): DimensionInterface
    {
        $dimension = $this->getSpecificDimensionOrNull($dimensionCollection, $dimensionAttributes);
        if (null !== $dimension) {
            return $dimension;
        }

        $dimension = $this->createDimension(
            $dimensionCollection->getDimensionClass(),
            $dimensionCollection->getId(),
            $dimensionAttributes
        );

        $dimensionCollection->setDimensions(
            $this->sortCollection(
                $dimensionCollection->getDimensions(),
                $dimensionCollection->getDimensionAttributes()
            )
        );

        return $dimension;
    }

    /**
     * @param array<string, mixed|null> $dimensionAttributes
     */
    protected function getSpecificDimensionOrNull(DimensionCollectionInterface $dimensionCollection, array $dimensionAttributes): ?DimensionInterface
    {
        // Get most specific dimension
        $dimensionAttributes = array_merge(
            $dimensionCollection->getDimensionAttributes(),
            $dimensionAttributes
        );

        Assert::isMap($dimensionAttributes);

        $criteria = Criteria::create();
        foreach ($dimensionAttributes as $attribute => $value) {
            if (null === $value) {
                $criteria->andWhere(
                    $criteria::expr()->isNull($attribute)
                );

                continue;
            }

            $criteria->andWhere(
                $criteria::expr()->eq($attribute, $value)
            );
        }

        /** @var Collection<int, DimensionInterface> $collection */
        $collection = $dimensionCollection->getDimensions()->matching($criteria);
        $collection = $this->sortCollection($collection, $dimensionCollection->getDimensionAttributes(), 'DESC');

        return $collection->first() ?: null;
    }

    /**
     * @param Collection<int, DimensionInterface> $collection
     * @param array<string, mixed> $dimensionAttributes
     *
     * @return Collection<int, DimensionInterface>
     */
    protected function sortCollection(Collection $collection, $dimensionAttributes, string $sortMethod = 'ASC'): Collection
    {
        $criteria = Criteria::create()
            ->orderBy(
                array_fill_keys(
                    array_keys($dimensionAttributes),
                    $sortMethod
                )
            );

        return $collection->matching($criteria);
    }

    /**
     * @param DimensionInterface[] $dimensions
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    protected function createSortedDimensionCollection(
        array $dimensions,
        string $dimensionClass,
        string $id,
        array $dimensionAttributes
    ): DimensionCollectionInterface {
        $collection = $this->sortCollection(new ArrayCollection($dimensions), $dimensionAttributes);

        return new DimensionCollection(
            $collection,
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
}
