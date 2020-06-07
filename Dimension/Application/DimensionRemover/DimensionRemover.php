<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionRemover;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Repository\DimensionRepositoryInterface;
use Webmozart\Assert\Assert;

class DimensionRemover implements DimensionRemoverInterface
{
    protected DimensionRepositoryInterface $dimensionRepository;

    public function __construct(DimensionRepositoryInterface $dimensionRepository)
    {
        $this->dimensionRepository = $dimensionRepository;
    }

    public function remove(string $dimensionClass, string $id): void
    {
        $this->dimensionRepository->remove($dimensionClass, $id);
    }

    public function removeDimension(string $dimensionClass, string $id, array $dimensionAttributes): void
    {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $this->dimensionRepository->removeByDimensionAttributes(
            $dimensionClass,
            $id,
            $dimensionAttributes
        );
    }
}
