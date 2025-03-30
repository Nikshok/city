<?php

namespace Component\City\Application\Contract\Response;

class CityDto
{
    private int $id;
    private string $name;
    private string $alias;
    private int $weight;
    private string $country;
    private string $region;

    public function __construct(int $id, string $name, string $alias, int $weight, string $country, string $region)
    {
        $this->id = $id;
        $this->name = $name;
        $this->alias = $alias;
        $this->weight = $weight;
        $this->country = $country;
        $this->region = $region;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'alias' => $this->getAlias(),
            'country' => $this->getCountry(),
            'region' => $this->getRegion(),
        ];
    }
}
