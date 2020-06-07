<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Model;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Webmozart\Assert\Assert;

/**
 * @implements \IteratorAggregate<int, DimensionInterface>
 */
class DimensionCollection implements \IteratorAggregate, DimensionCollectionInterface
{
    /**
     * @var DimensionInterface[]
     */
    protected array $dimensions;

    /**
     * @var class-string<DimensionInterface>
     */
    protected string $dimensionClass;

    protected string $id;

    /**
     * @var array<string, mixed>
     */
    protected array $dimensionAttributes;

    /**
     * @param DimensionInterface[] $dimensions
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function __construct(array $dimensions, string $dimensionClass, string $id, array $dimensionAttributes)
    {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $this->dimensions = $dimensions;
        $this->dimensionClass = $dimensionClass;
        $this->id = $id;
        $this->dimensionAttributes = $dimensionAttributes;
    }

    public function getDimensionClass(): string
    {
        return $this->dimensionClass;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDimensionAttributes(): array
    {
        return $this->dimensionAttributes;
    }

    public function getSpecificDimension(array $dimensionAttributes): ?DimensionInterface
    {
        $dimensionAttributes = array_merge(
            $this->getDimensionAttributes(),
            $dimensionAttributes
        );

        Assert::isMap($dimensionAttributes);

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($this->dimensions as $dimension) {
            foreach ($dimensionAttributes as $attribute => $value) {
                if ($value !== $propertyAccessor->getValue($dimension, $attribute)) {
                    continue 2;
                }
            }

            return $dimension;
        }

        return null;
    }

    /**
     * @return \ArrayIterator<int, DimensionInterface>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->dimensions);
    }

    public function count(): int
    {
        return $this->getIterator()->count();
    }
}
