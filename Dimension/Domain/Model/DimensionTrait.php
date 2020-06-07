<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Model;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Webmozart\Assert\Assert;

/**
 * @mixin DimensionInterface
 */
trait DimensionTrait
{
    protected string $id;

    protected bool $projection = false;

    public function getId(): string
    {
        return $this->id;
    }

    public function setDimensionAttributes(array $dimensionAttributes): void
    {
        Assert::isMap($dimensionAttributes);

        if ($this->isProjection()) {
            Assert::allNotNull($dimensionAttributes);
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($dimensionAttributes as $attribute => $value) {
            $propertyAccessor->setValue($this, $attribute, $value);
        }
    }

    public function isProjection(): bool
    {
        return $this->projection;
    }

    public function markAsProjection(): void
    {
        $this->projection = true;
    }

    public static function createProjection(string $id, array $dimensionAttributes): DimensionInterface
    {
        Assert::isMap($dimensionAttributes);
        Assert::allNotNull($dimensionAttributes);

        $dimensionContent = static::create($id, $dimensionAttributes);
        $dimensionContent->markAsProjection();

        return $dimensionContent;
    }

    public static function getAvailableDimensionAttributes(): array
    {
        $reflClass = new \ReflectionClass(static::class);

        Assert::true(
            $reflClass->implementsInterface(DimensionInterface::class),
            sprintf('"%s" needs to implement "%s".', static::class, DimensionInterface::class)
        );

        /** @var DimensionInterface $instance */
        $instance = $reflClass->newInstanceWithoutConstructor();

        try {
            $availableDimensionAttributes = array_keys($instance->getDimensionAttributes());
        } catch (\Error $error) {
            if (!preg_match('/^Typed property .+ must not be accessed before initialization$/', $error->getMessage())) {
                throw $error;
            }

            throw new \RuntimeException(
                sprintf('All dimension attributes of "%s" must be "null" by default.', static::class),
                0,
                $error
            );
        }

        Assert::isList($availableDimensionAttributes);
        Assert::allString($availableDimensionAttributes);

        return $availableDimensionAttributes;
    }

    abstract public static function create(string $id, array $dimensionAttributes): DimensionInterface;
}
