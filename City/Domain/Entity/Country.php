<?php

declare(strict_types=1);

namespace Component\City\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity()
 * @ORM\Table(name="citydb_country")
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="country_id")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="string", name="name", nullable=false)
     */
    private string $name;

    /**
     * @ORM\Column(type="boolean", name="active", nullable=false)
     */
    private bool $active;

    /**
     * @param string $name
     * @param bool $active
     */
    public function __construct(string $name, bool $active)
    {
        $this->name = $name;
        $this->active = $active;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Country
    {
        $this->name = $name;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): Country
    {
        $this->active = $active;
        return $this;
    }
}
