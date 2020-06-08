<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Functional\Dimension\Application\DimensionCopier;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Exception\DimensionNotFoundException;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\StageableInterface;
use LRH\Bundle\DimensionBundle\Tests\Application\Entity\Example;
use LRH\Bundle\DimensionBundle\Tests\Functional\BaseTestCase;
use LRH\Bundle\DimensionBundle\Tests\Functional\Traits\CreateExampleTrait;

class DimensionCopierTest extends BaseTestCase
{
    use CreateExampleTrait;

    public function testCopy(): void
    {
        $example = static::createExample();

        $copiedExample = static::getDimensionCopier()->copy(
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
            \get_class($copiedExample),
            $copiedExample->getId(),
            $copiedExample->getDimensionAttributes()
        );

        $this->assertSame($copiedExample->getDimensionAttributes(), $resolvedExample->getDimensionAttributes());
        $this->assertSame('test-name', $resolvedExample->getName());
        $this->assertSame('test-title', $resolvedExample->getTitle());
        $this->assertSame('test-location', $resolvedExample->getLocation());
        $this->assertSame('test-api-data', $resolvedExample->getApiData());

        $this->assertNotNull(
            static::getDimensionResolver()->resolve(
                \get_class($example),
                $example->getId(),
                $example->getDimensionAttributes()
            )
        );
    }

    public function testCopyNotFound(): void
    {
        $this->expectException(DimensionNotFoundException::class);

        static::getDimensionCopier()->copy(
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

    public function testCopyProjection(): void
    {
        $example = static::createExample();

        $copiedExample = static::getDimensionCopier()->copyProjection($example, array_merge($example->getDimensionAttributes(), [
            'stage' => StageableInterface::STAGE_LIVE,
        ]));
        static::getEntityManager()->flush();

        /** @var Example $resolvedExample */
        $resolvedExample = static::getDimensionResolver()->resolve(
            \get_class($copiedExample),
            $copiedExample->getId(),
            $copiedExample->getDimensionAttributes()
        );

        $this->assertSame($copiedExample->getDimensionAttributes(), $resolvedExample->getDimensionAttributes());
        $this->assertSame('test-name', $resolvedExample->getName());
        $this->assertSame('test-title', $resolvedExample->getTitle());
        $this->assertSame('test-location', $resolvedExample->getLocation());
        $this->assertSame('test-api-data', $resolvedExample->getApiData());

        $this->assertNotNull(
            static::getDimensionResolver()->resolve(
                \get_class($example),
                $example->getId(),
                $example->getDimensionAttributes()
            )
        );
    }
}
