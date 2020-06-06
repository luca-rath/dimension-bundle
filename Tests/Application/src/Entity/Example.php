<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Example
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected int $id;
}
