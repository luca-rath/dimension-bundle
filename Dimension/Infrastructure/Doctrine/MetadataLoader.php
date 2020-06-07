<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Infrastructure\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\LocalizableInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\StageableInterface;

class MetadataLoader implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $event): void
    {
        $metadata = $event->getClassMetadata();
        $builder = new ClassMetadataBuilder($metadata);
        $class = $metadata->getReflectionClass();

        $this->loadDimensionClassMetadata($builder, $class, $event);
        $this->loadLocalizableClassMetadata($builder, $class, $event);
        $this->loadStageableClassMetadata($builder, $class, $event);
    }

    /**
     * @param \ReflectionClass<DimensionInterface> $class
     */
    protected function loadDimensionClassMetadata(ClassMetadataBuilder $builder, \ReflectionClass $class, LoadClassMetadataEventArgs $event): void
    {
        if ($class->implementsInterface(DimensionInterface::class)) {
            $builder->addField(
                'id',
                'guid',
                [
                    'nullable' => true,
                ]
            );
        }
    }

    /**
     * @param \ReflectionClass<LocalizableInterface> $class
     */
    protected function loadLocalizableClassMetadata(ClassMetadataBuilder $builder, \ReflectionClass $class, LoadClassMetadataEventArgs $event): void
    {
        if ($class->implementsInterface(LocalizableInterface::class)) {
            $builder->addField(
                'locale',
                'string',
                [
                    'nullable' => true,
                ]
            );
        }
    }

    /**
     * @param \ReflectionClass<StageableInterface> $class
     */
    protected function loadStageableClassMetadata(ClassMetadataBuilder $builder, \ReflectionClass $class, LoadClassMetadataEventArgs $event): void
    {
        if ($class->implementsInterface(StageableInterface::class)) {
            $builder->addField(
                'stage',
                'string',
                [
                    'nullable' => true,
                ]
            );
        }
    }
}
