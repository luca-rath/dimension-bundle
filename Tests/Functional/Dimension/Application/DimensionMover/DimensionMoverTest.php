<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Functional\Dimension\Application\DimensionMover;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Exception\DimensionNotFoundException;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\StageableInterface;
use LRH\Bundle\DimensionBundle\Tests\Application\Entity\Example;
use LRH\Bundle\DimensionBundle\Tests\Functional\BaseTestCase;
use LRH\Bundle\DimensionBundle\Tests\Functional\Traits\CreateExampleTrait;

class DimensionMoverTest extends BaseTestCase
{
    use CreateExampleTrait;

    public function testMove(): void
    {
        $example = static::createExample();

        $movedExample = static::getDimensionMover()->move(
            \get_class($example),
            $example->getId(),
            $example->getDimensionAttributes(),
            array_merge($example->getDimensionAttributes(), [
                'stage' => StageableInterface::STAGE_LIVE,
            ])
        );
        static::getEntityManager()->flush();

        /** @var Example $resolvedExample */
        $resolvedExample = static::getDimensionResolver()->resolve(
            \get_class($movedExample),
            $movedExample->getId(),
            $movedExample->getDimensionAttributes()
        );

        $this->assertSame($movedExample->getDimensionAttributes(), $resolvedExample->getDimensionAttributes());
        $this->assertSame('test-name', $resolvedExample->getName());
        $this->assertSame('test-title', $resolvedExample->getTitle());
        $this->assertSame('test-location', $resolvedExample->getLocation());
        $this->assertSame('test-api-data', $resolvedExample->getApiData());

        $this->expectException(DimensionNotFoundException::class);
        static::getDimensionResolver()->resolve(
            \get_class($example),
            $example->getId(),
            $example->getDimensionAttributes()
        );
    }

    public function testMoveNotFound(): void
    {
        $this->expectException(DimensionNotFoundException::class);

        static::getDimensionMover()->move(
            Example::class,
            'not-existing-id',
            [
                'locale' => 'en',
                'stage' => StageableInterface::STAGE_DRAFT,
                'version' => 1,
            ],
            [
                'locale' => 'en',
                'stage' => StageableInterface::STAGE_LIVE,
                'version' => 1,
            ],
        );
    }

    public function testMoveProjection(): void
    {
        $example = static::createExample();

        $movedExample = static::getDimensionMover()->moveProjection($example, array_merge($example->getDimensionAttributes(), [
            'stage' => StageableInterface::STAGE_LIVE,
        ]));
        static::getEntityManager()->flush();

        /** @var Example $resolvedExample */
        $resolvedExample = static::getDimensionResolver()->resolve(
            \get_class($movedExample),
            $movedExample->getId(),
            $movedExample->getDimensionAttributes()
        );

        $this->assertSame($movedExample->getDimensionAttributes(), $resolvedExample->getDimensionAttributes());
        $this->assertSame('test-name', $resolvedExample->getName());
        $this->assertSame('test-title', $resolvedExample->getTitle());
        $this->assertSame('test-location', $resolvedExample->getLocation());
        $this->assertSame('test-api-data', $resolvedExample->getApiData());

        $this->expectException(DimensionNotFoundException::class);
        static::getDimensionResolver()->resolve(
            \get_class($example),
            $example->getId(),
            $example->getDimensionAttributes()
        );
    }
}
