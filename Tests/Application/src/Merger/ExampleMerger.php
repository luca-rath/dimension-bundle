<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Application\Merger;

use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMerger\MergerInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Factory\DimensionCollectionFactoryInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionCollectionInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use LRH\Bundle\DimensionBundle\Tests\Application\Entity\Example;
use Webmozart\Assert\Assert;

class ExampleMerger implements MergerInterface
{
    protected DimensionCollectionFactoryInterface $dimensionCollectionFactory;

    public function __construct(DimensionCollectionFactoryInterface $dimensionCollectionFactory)
    {
        $this->dimensionCollectionFactory = $dimensionCollectionFactory;
    }

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

        $this->getSpecificDimension($collection, [
            'locale' => null,
            'stage' => null,
        ])->setLocation($projection->getLocation());

        $this->getSpecificDimension($collection, [
            'locale' => $projection->getLocale(),
            'stage' => null,
        ])->setApiData($projection->getApiData());

        $this->getSpecificDimension($collection, [
            'locale' => null,
            'stage' => $projection->getStage(),
        ])->setName($projection->getName());

        $this->getSpecificDimension($collection, [
            'locale' => $projection->getLocale(),
            'stage' => $projection->getStage(),
        ])->setTitle($projection->getTitle());
    }

    /**
     * @param array<string, mixed|null> $dimensionAttributes
     */
    protected function getSpecificDimension(DimensionCollectionInterface $dimensionCollection, array $dimensionAttributes): Example
    {
        /** @var Example $dimension */
        $dimension = $this->dimensionCollectionFactory->getSpecificDimension($dimensionCollection, $dimensionAttributes);

        return $dimension;
    }
}
