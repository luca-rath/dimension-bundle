<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Exception;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

class DimensionNotFoundException extends \Exception
{
    /**
     * @param class-string<DimensionInterface> $dimensionClass
     * @param array<string, mixed> $dimensionAttributes
     */
    public function __construct(string $dimensionClass, string $id, array $dimensionAttributes)
    {
        parent::__construct(
            sprintf(
                '"%s" with id "%s" and dimension attributes "%s" not found.',
                $dimensionClass,
                $id,
                json_encode($dimensionAttributes)
            )
        );
    }
}
