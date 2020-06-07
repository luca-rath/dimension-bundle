<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMerger;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionCollectionInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface MergerInterface
{
    public function merge(DimensionCollectionInterface $collection, DimensionInterface $projection): void;

    public function unmerge(DimensionInterface $projection, DimensionCollectionInterface $collection): void;
}
