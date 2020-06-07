<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Application\Merger;

use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMerger\MergerInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionCollectionInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use LRH\Bundle\DimensionBundle\Tests\Application\Entity\Example;
use Webmozart\Assert\Assert;

class ExampleMerger implements MergerInterface
{
    public function merge(DimensionCollectionInterface $collection, DimensionInterface $projection): void
    {
        if (!$projection instanceof Example) {
            return;
        }

        Assert::true($projection->isProjection());
        Assert::allIsInstanceOf($collection, Example::class);

        /** @var Example $dimension */
        foreach ($collection as $dimension) {
            if ($title = $dimension->getTitle()) {
                $projection->setTitle($title);
            }

            if ($name = $dimension->getName()) {
                $projection->setName($name);
            }

            if ($location = $dimension->getLocation()) {
                $projection->setLocation($location);
            }

            if ($apiData = $dimension->getApiData()) {
                $projection->setApiData($apiData);
            }
        }
    }

    public function unmerge(DimensionInterface $projection, DimensionCollectionInterface $collection): void
    {
        if (!$projection instanceof Example) {
            return;
        }

        Assert::true($projection->isProjection());
        Assert::allIsInstanceOf($collection, Example::class);

        /** @var Example $unlocalizedDimensionNoStage */
        $unlocalizedDimensionNoStage = $collection->getSpecificDimension([
            'locale' => null,
            'stage' => null,
        ]);

        /** @var Example $localizedDimensionNoStage */
        $localizedDimensionNoStage = $collection->getSpecificDimension([
            'locale' => $projection->getLocale(),
            'stage' => null,
        ]);

        /** @var Example $unlocalizedDimension */
        $unlocalizedDimension = $collection->getSpecificDimension([
            'locale' => null,
            'stage' => $projection->getStage(),
        ]);

        /** @var Example $localizedDimension */
        $localizedDimension = $collection->getSpecificDimension([
            'locale' => $projection->getLocale(),
            'stage' => $projection->getStage(),
        ]);

        $unlocalizedDimensionNoStage->setLocation($projection->getLocation());
        $localizedDimensionNoStage->setApiData($projection->getApiData());
        $unlocalizedDimension->setName($projection->getName());
        $localizedDimension->setTitle($projection->getTitle());
    }
}
