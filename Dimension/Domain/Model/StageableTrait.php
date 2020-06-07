<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Model;

/**
 * @mixin StageableInterface
 */
trait StageableTrait
{
    protected ?string $stage = null;

    public function getStage(): ?string
    {
        return $this->stage;
    }

    public function setStage(?string $stage): void
    {
        $this->stage = $stage;
    }
}
