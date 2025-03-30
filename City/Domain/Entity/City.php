<?php

declare(strict_types=1);

namespace Component\City\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass="Component\City\Infrastructure\Repository\CityRepository")
 * @ORM\Table(name="citydb_city")
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="city_id")
     * @ORM\GeneratedValue
     */
    private int $id;

    /**
     * @ORM\Column(type="string", name="name", nullable=false)
     */
    private string $name;

    /**
     * @ORM\Column(type="integer", name="weight", nullable=false)
     */
    private int $weight;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id", onDelete="CASCADE")
     */
    private Country $country;

    /**
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="region_id", onDelete="CASCADE")
     */
    private Region $region;

    /**
     * @ORM\Column(type="string", name="tag", nullable=false)
     */
    private string $tag;

    /**
     * @ORM\Column(type="string", name="alt", nullable=false)
     */
    private string $alt;

    public function __construct(
        string $name,
        int $weight,
        Country $country,
        Region $region,
        string $tag,
        string $alt
    ) {
        $this->name = $name;
        $this->weight = $weight;
        $this->country = $country;
        $this->region = $region;
        $this->tag = $tag;
        $this->alt = $alt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): City
    {
        $this->name = $name;
        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): City
    {
        $this->weight = $weight;
        return $this;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): City
    {
        $this->country = $country;
        return $this;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): City
    {
        $this->region = $region;
        return $this;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): City
    {
        $this->tag = $tag;
        return $this;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): City
    {
        $this->alt = $alt;
        return $this;
    }
}
