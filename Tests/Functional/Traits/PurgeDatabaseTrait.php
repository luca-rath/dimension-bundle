<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Functional\Traits;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\ProxyReferenceRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Driver\PDOMySql\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait PurgeDatabaseTrait
{
    protected static function purgeDatabase(): void
    {
        if (!class_exists(ORMPurger::class)) {
            throw new \RuntimeException(
                'The composer package "doctrine/doctrine-fixtures-bundle" is required to purge the database'
            );
        }

        $entityManager = static::getEntityManager();
        $connection = $entityManager->getConnection();

        if ($connection->getDriver() instanceof Driver) {
            $connection->executeUpdate('SET foreign_key_checks = 0;');
        }

        $purger = new ORMPurger();
        $executor = new ORMExecutor($entityManager, $purger);
        $referenceRepository = new ProxyReferenceRepository($entityManager);
        $executor->setReferenceRepository($referenceRepository);
        $executor->purge();

        if ($connection->getDriver() instanceof Driver) {
            $connection->executeUpdate('SET foreign_key_checks = 1;');
        }
    }

    abstract protected static function getEntityManager(): EntityManagerInterface;

    abstract protected static function getContainer(): ContainerInterface;
}
