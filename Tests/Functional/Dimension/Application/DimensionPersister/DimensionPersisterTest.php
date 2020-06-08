<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Functional\Dimension\Application\DimensionPersister;

use LRH\Bundle\DimensionBundle\Tests\Application\Entity\Example;
use LRH\Bundle\DimensionBundle\Tests\Functional\BaseTestCase;
use LRH\Bundle\DimensionBundle\Tests\Functional\Traits\CreateExampleTrait;
use Symfony\Component\Uid\Uuid;

class DimensionPersisterTest extends BaseTestCase
{
    use CreateExampleTrait;

    public function testPersist(): void
    {
        $id = Uuid::v4()->toRfc4122();

        $example = Example::createProjection($id, [
            'locale' => 'en',
            'stage' => 'draft',
            'version' => 1,
        ]);

        $example->setName('name');
        $example->setTitle('title');
        $example->setLocation('location');
        $example->setApiData('api-data');

        static::getDimensionPersister()->persist($example);
        static::getEntityManager()->flush();

        /** @var Example $resolvedExample */
        $resolvedExample = static::getDimensionResolver()->resolve(
            \get_class($example),
            $example->getId(),
            $example->getDimensionAttributes()
        );

        $this->assertSame($example->getDimensionAttributes(), $resolvedExample->getDimensionAttributes());
        $this->assertSame('name', $resolvedExample->getName());
        $this->assertSame('title', $resolvedExample->getTitle());
        $this->assertSame('location', $resolvedExample->getLocation());
        $this->assertSame('api-data', $resolvedExample->getApiData());
    }

    public function testUpdate(): void
    {
        $example = static::createExample();

        $example->setName('new-name');
        $example->setTitle('new-title');
        $example->setLocation('new-location');
        $example->setApiData('new-api-data');

        static::getDimensionPersister()->persist($example);
        static::getEntityManager()->flush();

        /** @var Example $resolvedExample */
        $resolvedExample = static::getDimensionResolver()->resolve(
            \get_class($example),
            $example->getId(),
            $example->getDimensionAttributes(),
        );

        $this->assertSame($example->getDimensionAttributes(), $resolvedExample->getDimensionAttributes());
        $this->assertSame('new-name', $resolvedExample->getName());
        $this->assertSame('new-title', $resolvedExample->getTitle());
        $this->assertSame('new-location', $resolvedExample->getLocation());
        $this->assertSame('new-api-data', $resolvedExample->getApiData());
    }

    public function testPersistFromExisting(): void
    {
        $id = Uuid::v4()->toRfc4122();

        $oldExample = static::createExample($id, ['locale' => 'en']);
        $newExample = Example::createProjection($id, array_merge($oldExample->getDimensionAttributes(), [
            'locale' => 'de',
        ]));

        $newExample->setName('new-name');
        $newExample->setTitle('new-title');

        static::getDimensionPersister()->persist($newExample);
        static::getEntityManager()->flush();

        /** @var Example $resolvedNewExample */
        $resolvedNewExample = static::getDimensionResolver()->resolve(
            \get_class($newExample),
            $newExample->getId(),
            $newExample->getDimensionAttributes()
        );

        $this->assertSame('new-name', $resolvedNewExample->getName());
        $this->assertSame('new-title', $resolvedNewExample->getTitle());
        $this->assertSame('test-location', $resolvedNewExample->getLocation());
        $this->assertNull($resolvedNewExample->getApiData());

        /** @var Example $resolvedOldExample */
        $resolvedOldExample = static::getDimensionResolver()->resolve(
            \get_class($oldExample),
            $oldExample->getId(),
            $oldExample->getDimensionAttributes()
        );

        $this->assertSame('new-name', $resolvedOldExample->getName());
        $this->assertSame('test-title', $resolvedOldExample->getTitle());
        $this->assertSame('test-location', $resolvedOldExample->getLocation());
        $this->assertSame('test-api-data', $resolvedOldExample->getApiData());
    }
}
