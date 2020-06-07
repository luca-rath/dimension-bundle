<?php

declare(strict_types=1);

namespace LRH\Bundle\DimensionBundle\Tests\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\DimensionTrait;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\LocalizableInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\LocalizableTrait;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\StageableInterface;
use LRH\Bundle\DimensionBundle\Dimension\Domain\Model\StageableTrait;

/**
 * @ORM\Entity()
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="id_dimension_attributes", columns={"id", "locale", "stage", "version"})
 * })
 */
class Example implements DimensionInterface, LocalizableInterface, StageableInterface
{
    use DimensionTrait {
        createProjection as parentCreateProjection;
    }
    use LocalizableTrait;
    use StageableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected int $no;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected ?int $version = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $name = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $title = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $location = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $apiData = null;

    /**
     * @param array<string, mixed|null> $dimensionAttributes
     */
    public function __construct(string $id, array $dimensionAttributes)
    {
        $this->id = $id;
        $this->setDimensionAttributes($dimensionAttributes);
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): void
    {
        $this->version = $version;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getApiData(): ?string
    {
        return $this->apiData;
    }

    public function setApiData(?string $apiData): void
    {
        $this->apiData = $apiData;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDimensionAttributes(): array
    {
        return [
            'locale' => $this->getLocale(),
            'stage' => $this->getStage(),
            'version' => $this->getVersion(),
        ];
    }

    public static function create(string $id, array $dimensionAttributes): self
    {
        return new self($id, $dimensionAttributes);
    }

    public static function createProjection(string $id, array $dimensionAttributes): self
    {
        /** @var Example $projection */
        $projection = self::parentCreateProjection($id, $dimensionAttributes);

        return $projection;
    }
}
