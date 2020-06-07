<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Model;

interface LocalizableInterface
{
    public function getLocale(): ?string;

    public function setLocale(?string $locale): void;
}
