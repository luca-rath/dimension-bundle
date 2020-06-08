<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Functional\Dimension\Application\DimensionResolver;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Exception\DimensionNotFoundException;
use LRH\Bundle\DimensionBundle\Tests\Application\Entity\Example;
use LRH\Bundle\DimensionBundle\Tests\Functional\BaseTestCase;
use LRH\Bundle\DimensionBundle\Tests\Functional\Traits\CreateExampleTrait;

class DimensionResolverTest extends BaseTestCase
{
    use CreateExampleTrait;

    public function testResolve(): void
    {
        $example = static::createExample();

        /** @var Example $resolvedExample */
        $resolvedExample = static::getDimensionResolver()->resolve(
            \get_class($example),
            $example->getId(),
            $example->getDimensionAttributes()
        );

        $this->assertSame($example->getDimensionAttributes(), $resolvedExample->getDimensionAttributes());
        $this->assertSame('test-name', $resolvedExample->getName());
        $this->assertSame('test-title', $resolvedExample->getTitle());
        $this->assertSame('test-location', $resolvedExample->getLocation());
        $this->assertSame('test-api-data', $resolvedExample->getApiData());
    }

    public function testResolveNotFound(): void
    {
        $example = static::createExample(null, ['locale' => 'en']);

        $this->expectException(DimensionNotFoundException::class);
        static::getDimensionResolver()->resolve(
            \get_class($example),
            $example->getId(),
            array_merge($example->getDimensionAttributes(), [
                'locale' => 'de',
            ])
        );
    }

    public function testResolvePartial(): void
    {
        $example = static::createExample(null, ['locale' => 'en']);

        /** @var Example $resolvedExample */
        $resolvedExample = static::getDimensionResolver()->resolvePartial(
            \get_class($example),
            $example->getId(),
            array_merge($example->getDimensionAttributes(), [
                'locale' => 'de',
            ])
        );

        $this->assertSame('test-name', $resolvedExample->getName());
        $this->assertNull($resolvedExample->getTitle());
        $this->assertSame('test-location', $resolvedExample->getLocation());
        $this->assertNull($resolvedExample->getApiData());
    }
}
