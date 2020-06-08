<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Model;

use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;

/**
 * @implements \IteratorAggregate<int, DimensionInterface>
 */
class DimensionCollection implements \IteratorAggregate, DimensionCollectionInterface
{
    /**
     * @var Collection<int, DimensionInterface>
     */
    protected Collection $dimensions;

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
     * @param Collection<int, DimensionInterface> $dimensions
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function __construct(Collection $dimensions, string $dimensionClass, string $id, array $dimensionAttributes)
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

    public function getDimensions(): Collection
    {
        return $this->dimensions;
    }

    public function setDimensions(Collection $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    /**
     * @return \Traversable<int, DimensionInterface>
     */
    public function getIterator(): \Traversable
    {
        return $this->dimensions->getIterator();
    }

    public function count(): int
    {
        return $this->dimensions->count();
    }
}
