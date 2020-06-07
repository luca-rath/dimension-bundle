<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Application\Repository;

use Doctrine\ORM\EntityManagerInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Factory\DimensionFactoryInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Repository\DimensionRepositoryInterface;
use Webmozart\Assert\Assert;

class DimensionRepository implements DimensionRepositoryInterface
{
    protected EntityManagerInterface $entityManager;
    protected DimensionFactoryInterface $dimensionFactory;

    public function __construct(EntityManagerInterface $entityManager, DimensionFactoryInterface $dimensionFactory)
    {
        $this->entityManager = $entityManager;
        $this->dimensionFactory = $dimensionFactory;
    }

    public function create(string $dimensionClass, string $id, array $dimensionAttributes): DimensionInterface
    {
        return $this->dimensionFactory->createDimension($dimensionClass, $id, $dimensionAttributes);
    }

    public function add(DimensionInterface $dimension): void
    {
        Assert::false($dimension->isProjection(), 'Projection cannot be persisted.');

        $this->entityManager->persist($dimension);
    }

    public function findByDimensionAttributes(string $dimensionClass, string $id, array $dimensionAttributes): array
    {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('dimension')
            ->from($dimensionClass, 'dimension')
            ->where('dimension.id = :id')
            ->setParameter('id', $id);

        foreach ($dimensionAttributes as $attribute => $value) {
            $qb->addOrderBy('dimension.' . $attribute, 'ASC');

            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->isNull('dimension.' . $attribute),
                    $qb->expr()->eq('dimension.' . $attribute, ':' . $attribute)
                )
            );

            $qb->setParameter($attribute, $value);
        }

        return $qb->getQuery()->getResult();
    }

    public function findOneByDimensionAttributes(string $dimensionClass, string $id, array $dimensionAttributes): ?DimensionInterface
    {
        Assert::isMap($dimensionAttributes);

        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->select('dimension')
            ->from($dimensionClass, 'dimension')
            ->where('dimension.id = :id')
            ->setParameter('id', $id);

        foreach ($dimensionAttributes as $attribute => $value) {
            $qb->andWhere(
                $qb->expr()->eq('dimension.' . $attribute, ':' . $attribute)
            );

            $qb->setParameter($attribute, $value);
        }

        return $qb->getQuery()->getSingleResult();
    }

    public function remove(string $dimensionClass, string $id): void
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->delete($dimensionClass, 'dimension')
            ->where('dimension.id = :id')
            ->setParameter('id', $id);

        $qb->getQuery()->execute();
    }

    public function removeByDimensionAttributes(string $dimensionClass, string $id, array $dimensionAttributes): void
    {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $qb = $this->entityManager->createQueryBuilder();
        $qb
            ->delete($dimensionClass, 'dimension')
            ->where('dimension.id = :id')
            ->setParameter('id', $id);

        foreach ($dimensionAttributes as $attribute => $value) {
            $qb->andWhere(
                $qb->expr()->eq('dimension.' . $attribute, ':' . $attribute)
            );

            $qb->setParameter($attribute, $value);
        }

        $qb->getQuery()->execute();
    }
}
