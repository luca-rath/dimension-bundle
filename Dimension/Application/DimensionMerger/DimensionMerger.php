<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMerger;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionCollectionInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use Webmozart\Assert\Assert;

class DimensionMerger implements DimensionMergerInterface
{
    /**
     * @var iterable<MergerInterface>
     */
    protected iterable $mergers;

    /**
     * @param iterable<MergerInterface> $mergers
     */
    public function __construct(iterable $mergers)
    {
        $this->mergers = $mergers;
    }

    public function merge(DimensionCollectionInterface $collection, DimensionInterface $projection): void
    {
        Assert::true($projection->isProjection(), '"$projection" needs to be a projection.');

        /** @var MergerInterface $merger */
        foreach ($this->mergers as $merger) {
            $merger->merge($collection, $projection);
        }
    }

    public function unmerge(DimensionInterface $projection, DimensionCollectionInterface $collection): void
    {
        Assert::true($projection->isProjection(), '"$projection" needs to be a projection.');

        /** @var MergerInterface $merger */
        foreach ($this->mergers as $merger) {
            $merger->unmerge($projection, $collection);
        }
    }
}
