<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionCopier\DimensionCopierInterface;
use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionMover\DimensionMoverInterface;
use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionPersister\DimensionPersisterInterface;
use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionRemover\DimensionRemoverInterface;
use LRH\Bundle\DimensionBundle\Dimension\Application\DimensionResolver\DimensionResolverInterface;
use LRH\Bundle\DimensionBundle\Tests\Functional\Traits\PurgeDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseTestCase extends KernelTestCase
{
    use PurgeDatabaseTrait;

    public static function setUpBeforeClass(): void
    {
        static::purgeDatabase();
        parent::setUpBeforeClass();
    }

    protected function setUp(): void
    {
        if (!self::$booted) {
            static::bootKernel();
        }

        parent::setUp();
    }

    protected static function getContainer(): ContainerInterface
    {
        if (!self::$booted) {
            static::bootKernel();
        }

        return static::$container;
    }

    protected static function getEntityManager(): EntityManagerInterface
    {
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get('doctrine.orm.entity_manager');

        return $em;
    }

    protected static function getDimensionResolver(): DimensionResolverInterface
    {
        return static::getContainer()->get(DimensionResolverInterface::class);
    }

    protected static function getDimensionPersister(): DimensionPersisterInterface
    {
        return static::getContainer()->get(DimensionPersisterInterface::class);
    }

    protected static function getDimensionCopier(): DimensionCopierInterface
    {
        return static::getContainer()->get(DimensionCopierInterface::class);
    }

    protected static function getDimensionMover(): DimensionMoverInterface
    {
        return static::getContainer()->get(DimensionMoverInterface::class);
    }

    protected static function getDimensionRemover(): DimensionRemoverInterface
    {
        return static::getContainer()->get(DimensionRemoverInterface::class);
    }
}
