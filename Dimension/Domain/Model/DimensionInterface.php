<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Model;

interface DimensionInterface
{
    public function getId(): string;

    /**
     * @return array<string, mixed|null>
     */
    public function getDimensionAttributes(): array;

    /**
     * @param array<string, mixed|null> $dimensionAttributes
     */
    public function setDimensionAttributes(array $dimensionAttributes): void;

    /**
     * @return string[]
     */
    public static function getAvailableDimensionAttributes(): array;

    /**
     * @param array<string, mixed|null> $dimensionAttributes
     */
    public static function create(string $id, array $dimensionAttributes): self;

    /**
     * @param array<string, mixed> $dimensionAttributes
     */
    public static function createProjection(string $id, array $dimensionAttributes): self;

    public function isProjection(): bool;

    public function markAsProjection(): void;
}
