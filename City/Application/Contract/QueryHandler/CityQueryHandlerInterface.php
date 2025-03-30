<?php

namespace Component\City\Application\Contract\QueryHandler;

use Component\City\Application\Contract\Response\CityDto;

interface CityQueryHandlerInterface
{
    public function getCityById(int $id): ?CityDto;

    public function getCityByName(string $cityName, bool $strictComparison = false): ?CityDto;

    /** @return list<CityDto> */
    public function getCitiesByTag(string $tagName, ?int $countryId = null): array;

    /**
     * @param string|null $name
     * @param bool|null $activeCountries
     * @param int|null $countryId
     * @param bool $excludeOnlineCity
     * @return array<int, CityDto>
     */
    public function getCities(?string $name = null, ?bool $activeCountries = null, ?int $countryId = null, bool $excludeOnlineCity = false): array;
}
