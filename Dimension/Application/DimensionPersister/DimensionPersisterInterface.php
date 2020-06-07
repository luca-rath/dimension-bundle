<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionPersister;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;

interface DimensionPersisterInterface
{
    public function persist(DimensionInterface $projection): void;
}
