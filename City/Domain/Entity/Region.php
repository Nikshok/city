<?php

declare(strict_types=1);

namespace Component\City\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity()
 * @ORM\Table(name="citydb_region")
 */
class Region
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="region_id")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="string", name="name", nullable=false)
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id", onDelete="CASCADE")
     */
    private Country $country;

    /**
     * @param string $name
     * @param Country $country
     */
    public function __construct(string $name, Country $country)
    {
        $this->name = $name;
        $this->country = $country;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Region
    {
        $this->name = $name;
        return $this;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): Region
    {
        $this->country = $country;
        return $this;
    }
}
