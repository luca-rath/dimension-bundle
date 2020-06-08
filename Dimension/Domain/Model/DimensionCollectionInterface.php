<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Model;

use Doctrine\Common\Collections\Collection;

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
     * @return Collection<int, DimensionInterface>
     *
     * @internal
     */
    public function getDimensions(): Collection;

    /**
     * @param Collection<int, DimensionInterface> $dimensions
     *
     * @internal
     */
    public function setDimensions(Collection $dimensions): void;
}
