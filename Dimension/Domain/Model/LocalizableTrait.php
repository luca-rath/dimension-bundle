<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Model;

/**
 * @mixin LocalizableInterface
 */
trait LocalizableTrait
{
    protected ?string $locale = null;

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): void
    {
        $this->locale = $locale;
    }
}
