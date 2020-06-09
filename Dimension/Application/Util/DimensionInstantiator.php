<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\Util;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

/**
 * @internal
 */
final class DimensionInstantiator
{
    private function __construct()
    {
    }

    /**
     * @param class-string<DimensionInterface> $dimensionClass
     */
    public static function createInstance(string $dimensionClass): DimensionInterface
    {
        $reflClass = new \ReflectionClass($dimensionClass);

        if (!$reflClass->implementsInterface(DimensionInterface::class)) {
            throw new \InvalidArgumentException(
                sprintf('"$dimensionClass" needs to implement "%s".', DimensionInterface::class)
            );
        }

        /** @var DimensionInterface $instance */
        $instance = $reflClass->newInstanceWithoutConstructor();

        return $instance;
    }
}
