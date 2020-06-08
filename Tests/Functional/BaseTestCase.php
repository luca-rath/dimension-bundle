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

    protected static function getEntityManager(): EntityManagerInterface
    {
        if (!self::$booted) {
            static::bootKernel();
        }

        /** @var EntityManagerInterface $em */
        $em = static::$container->get('doctrine.orm.entity_manager');

        return $em;
    }

    protected static function getContainer(): ContainerInterface
    {
        if (!self::$booted) {
            static::bootKernel();
        }

        return static::$container;
    }

    protected static function getDimensionResolver(): DimensionResolverInterface
    {
        if (!self::$booted) {
            static::bootKernel();
        }

        return static::$container->get(DimensionResolverInterface::class);
    }

    protected static function getDimensionPersister(): DimensionPersisterInterface
    {
        if (!self::$booted) {
            static::bootKernel();
        }

        return static::$container->get(DimensionPersisterInterface::class);
    }

    protected static function getDimensionCopier(): DimensionCopierInterface
    {
        if (!self::$booted) {
            static::bootKernel();
        }

        return static::$container->get(DimensionCopierInterface::class);
    }

    protected static function getDimensionMover(): DimensionMoverInterface
    {
        if (!self::$booted) {
            static::bootKernel();
        }

        return static::$container->get(DimensionMoverInterface::class);
    }

    protected static function getDimensionRemover(): DimensionRemoverInterface
    {
        if (!self::$booted) {
            static::bootKernel();
        }

        return static::$container->get(DimensionRemoverInterface::class);
    }
}
