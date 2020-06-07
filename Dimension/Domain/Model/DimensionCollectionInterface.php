<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Model;

/**
 * @extends \Traversable<int, DimensionInterface>
 */
interface DimensionCollectionInterface extends \Traversable, \Countable
{
    /**
     * @return class-string<DimensionInterface>
     */
    public function getDimensionClass(): string;

    public function getId(): string;

    /**
     * @return array<string, mixed>
     */
    public function getDimensionAttributes(): array;

    /**
     * @param array<string, mixed|null> $dimensionAttributes
     */
    public function getSpecificDimension(array $dimensionAttributes): ?DimensionInterface;
}
