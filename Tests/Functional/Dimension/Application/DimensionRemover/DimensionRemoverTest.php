<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Functional\Dimension\Application\DimensionRemover;

use LRH\Bundle\DimensionBundle\Dimension\Domain\Exception\DimensionNotFoundException;
use LRH\Bundle\DimensionBundle\Tests\Application\Entity\Example;
use LRH\Bundle\DimensionBundle\Tests\Functional\BaseTestCase;
use LRH\Bundle\DimensionBundle\Tests\Functional\Traits\CreateExampleTrait;
use Symfony\Component\Uid\Uuid;

class DimensionRemoverTest extends BaseTestCase
{
    use CreateExampleTrait;

    public function testRemove(): void
    {
        $id = Uuid::v4()->toRfc4122();

        static::createExample($id, ['locale' => 'en']);
        static::createExample($id, ['locale' => 'de']);

        static::getDimensionRemover()->remove(Example::class, $id);
        static::getEntityManager()->flush();

        $repository = static::getEntityManager()->getRepository(Example::class);
        $this->assertEmpty($repository->findBy(['id' => $id]));
    }

    public function testRemoveDimension(): void
    {
        $id = Uuid::v4()->toRfc4122();

        $enExample = static::createExample($id, ['locale' => 'en']);
        $deExample = static::createExample($id, ['locale' => 'de']);

        static::getDimensionRemover()->removeDimension(
            \get_class($deExample),
            $deExample->getId(),
            $deExample->getDimensionAttributes()
        );
        static::getEntityManager()->flush();

        $this->assertNotNull(
            static::getDimensionResolver()->resolve(
                \get_class($enExample),
                $enExample->getId(),
                $enExample->getDimensionAttributes()
            )
        );

        $this->expectException(DimensionNotFoundException::class);
        static::getDimensionResolver()->resolve(
            \get_class($deExample),
            $deExample->getId(),
            $deExample->getDimensionAttributes()
        );
    }
}
