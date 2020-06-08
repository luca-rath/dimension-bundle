<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Functional\Traits;

use Doctrine\ORM\EntityManagerInterface;
use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionPersister\DimensionPersisterInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\StageableInterface;
use LRH\Bundle\DimensionBundle\Tests\Application\Entity\Example;
use Symfony\Component\Uid\Uuid;

trait CreateExampleTrait
{
    /**
     * @param string $id
     * @param array<string, mixed> $dimensionAttributes
     */
    protected function createExample(string $id = null, array $dimensionAttributes = []): Example
    {
        if (null === $id) {
            $id = Uuid::v4()->toRfc4122();
        }

        $example = Example::createProjection($id, array_merge([
            'locale' => 'en',
            'stage' => StageableInterface::STAGE_DRAFT,
            'version' => 1,
        ], $dimensionAttributes));

        $example->setName('test-name');
        $example->setTitle('test-title');
        $example->setLocation('test-location');
        $example->setApiData('test-api-data');

        static::getDimensionPersister()->persist($example);
        static::getEntityManager()->flush();

        return $example;
    }

    abstract protected static function getEntityManager(): EntityManagerInterface;

    abstract protected static function getDimensionPersister(): DimensionPersisterInterface;
}
