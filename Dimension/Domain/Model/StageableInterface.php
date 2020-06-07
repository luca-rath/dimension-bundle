<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Dimension\Domain\Model;

interface StageableInterface
{
    const STAGE_DRAFT = 'draft';
    const STAGE_LIVE = 'live';

    public function getStage(): ?string;

    public function setStage(?string $stage): void;
}
