<?php

declare(strict_types=1);

namespace Component\City\Infrastructure\QueryHandler;

use Component\City\Application\Contract\Enum\CityEnum;
use Component\City\Application\Contract\QueryHandler\CityQueryHandlerInterface;
use Component\City\Application\Contract\Response\CityDto;
use Component\Infrastructure\Exception\NotFoundException;
use Component\Infrastructure\Helper\ArrayReader\ArrayValueTypedReader;
use Component\Infrastructure\Helper\ArrayReader\KeyIsNotExistsException;
use Component\Infrastructure\Helper\ArrayReader\ValueCastException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ForwardCompatibility\Result;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CityQueryHandler implements CityQueryHandlerInterface
{
    private Connection $connection;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->connection = $entityManager->getConnection();
    }

    /**
     * @param int $id
     * @return CityDto|null
     * @throws KeyIsNotExistsException
     * @throws NotFoundException
     * @throws ValueCastException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getCityById(int $id): ?CityDto
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'c.city_id as id',
                'c.name',
                'c.tag',
                'c.weight',
                'cc.name as country_name',
                'cr.name as region_name'
            )
            ->from('citydb_city', 'c')
            ->innerJoin('c', 'citydb_country', 'cc', 'c.country_id = cc.country_id')
            ->innerJoin('c', 'citydb_region', 'cr', 'c.region_id = cr.region_id')
            ->where('c.city_id = :id')
            ->setParameter('id', $id);

        $result = $qb->execute();

        if (!($result instanceof Result)) {
            throw new Exception("Произошла ошибка в sql запросе. Возвращено значение - " . $result);
        }

        /**
         * @var array{
         *     id: int,
         *     name: string,
         *     tag: string,
         *     weight: int,
         *     country_name: string,
         *     region_name: string,
         * } $city
         */
        $city = $result->fetchAssociative();

        if (!$city) {
            throw new NotFoundException(sprintf('City %d not found', $id));
        }

        return new CityDto(
            ArrayValueTypedReader::getInt($city, 'id'),
            ArrayValueTypedReader::getString($city, 'name'),
            ArrayValueTypedReader::getString($city, 'tag'),
            ArrayValueTypedReader::getInt($city, 'weight'),
            ArrayValueTypedReader::getString($city, 'country_name'),
            ArrayValueTypedReader::getString($city, 'region_name'),
        );
    }

    /**
     * @param string $cityName
     * @param bool $strictComparison
     * @return CityDto|null
     * @throws KeyIsNotExistsException
     * @throws NotFoundException
     * @throws ValueCastException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getCityByName(string $cityName, bool $strictComparison = false): ?CityDto
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'c.city_id as id',
                'c.name',
                'c.tag',
                'c.weight',
                'cc.name as country_name',
                'cr.name as region_name'
            )
            ->from('citydb_city', 'c')
            ->innerJoin('c', 'citydb_country', 'cc', 'c.country_id = cc.country_id')
            ->innerJoin('c', 'citydb_region', 'cr', 'c.region_id = cr.region_id');

        if ($strictComparison) {
            $qb->andWhere('c.name = :name')
                ->setParameter('name', $cityName);
        } else {
            $qb->andWhere('c.name LIKE :name OR c.alt LIKE :alt')
                ->setParameter('name', $cityName . '%')
                ->setParameter('alt', '%' . $cityName . '%');
        }

        $result = $qb->execute();

        if (!($result instanceof Result)) {
            throw new Exception("Произошла ошибка в sql запросе. Возвращено значение - " . $result);
        }

        /**
         * @var array{
         *     id: int,
         *     name: string,
         *     tag: string,
         *     weight: int,
         *     country_name: string,
         *     region_name: string,
         * } $city
         */
        $city = $result->fetchAssociative();

        if (!$city) {
            throw new NotFoundException(sprintf('City %d not found', $cityName));
        }

        return new CityDto(
            ArrayValueTypedReader::getInt($city, 'id'),
            ArrayValueTypedReader::getString($city, 'name'),
            ArrayValueTypedReader::getString($city, 'tag'),
            ArrayValueTypedReader::getInt($city, 'weight'),
            ArrayValueTypedReader::getString($city, 'country_name'),
            ArrayValueTypedReader::getString($city, 'region_name'),
        );
    }

    /**
     * @param string $tagName
     * @param int|null $countryId
     * @return array|CityDto[]
     * @throws KeyIsNotExistsException
     * @throws ValueCastException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getCitiesByTag(string $tagName, ?int $countryId = null): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'c.city_id as id',
                'c.name',
                'c.tag',
                'c.weight',
                'cc.name as country_name',
                'cr.name as region_name'
            )
            ->from('citydb_city', 'c')
            ->innerJoin('c', 'citydb_country', 'cc', 'c.country_id = cc.country_id')
            ->innerJoin('c', 'citydb_region', 'cr', 'c.region_id = cr.region_id')
            ->andWhere('c.tag = :tagName')
            ->setParameter('tagName', $tagName);

        if ($countryId !== null) {
            $qb->andWhere('c.country_id = :countryId')
                ->setParameter('countryId', $countryId);
        }

        $result = $qb->execute();

        if (!($result instanceof Result)) {
            throw new Exception("Произошла ошибка в sql запросе. Возвращено значение - " . $result);
        }

        /**
         * @var array<int, array{
         *     id: int,
         *     name: string,
         *     tag: string,
         *     weight: int,
         *     country_name: string,
         *     region_name: string,
         * }> $cities
         */
        $cities = $result->fetchAllAssociative();

        $citiesDto = [];
        foreach ($cities as $city) {
            $cityDto = new CityDto(
                ArrayValueTypedReader::getInt($city, 'id'),
                ArrayValueTypedReader::getString($city, 'name'),
                ArrayValueTypedReader::getString($city, 'tag'),
                ArrayValueTypedReader::getInt($city, 'weight'),
                ArrayValueTypedReader::getString($city, 'country_name'),
                ArrayValueTypedReader::getString($city, 'region_name'),
            );

            if ($cityDto !== null) {
                $citiesDto[] = $cityDto;
            }
        }

        return $citiesDto;
    }

    /**
     * @param string|null $name
     * @param bool|null $activeCountries
     * @param int|null $countryId
     * @param bool $excludeOnlineCity
     * @return array|CityDto[]
     * @throws KeyIsNotExistsException
     * @throws ValueCastException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function getCities(
        ?string $name = null,
        ?bool $activeCountries = null,
        ?int $countryId = null,
        bool $excludeOnlineCity = false
    ): array {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'c.city_id as id',
                'c.name',
                'c.tag',
                'c.weight',
                'cc.name as country_name',
                'cr.name as region_name'
            )
            ->from('citydb_city', 'c')
            ->innerJoin(
                'c',
                'citydb_country',
                'cc',
                'c.country_id = cc.country_id'
            )
            ->innerJoin(
                'c',
                'citydb_region',
                'cr',
                'c.region_id = cr.region_id'
            );

        if ($excludeOnlineCity) {
            $qb->andWhere('c.name != \'' . CityEnum::CITY_WITHOUT_CITY . '\'');
        }

        if ($name) {
            $qb->andWhere('c.name LIKE :name OR c.alt LIKE :alt')
                ->setParameter('name', $name . '%')
                ->setParameter('alt', '%' . $name . '%');
        }

        if ($countryId) {
            $qb->andWhere('c.country_id = :countryId')
                ->setParameter('countryId', $countryId);
        }

        if ($activeCountries === true) {
            $qb->andWhere('cc.active = 1');
        } elseif ($activeCountries === false) {
            $qb->andWhere('cc.active = 0');
        }

        $result = $qb->execute();

        if (!($result instanceof Result)) {
            throw new Exception("Произошла ошибка в sql запросе. Возвращено значение - " . $result);
        }

        /**
         * @var array<int, array{
         *     id: int,
         *     name: string,
         *     tag: string,
         *     weight: int,
         *     country_name: string,
         *     region_name: string,
         * }> $cities
         */
        $cities = $result->fetchAllAssociative();

        $citiesDto = [];
        foreach ($cities as $city) {
            $cityDto = new CityDto(
                ArrayValueTypedReader::getInt($city, 'id'),
                ArrayValueTypedReader::getString($city, 'name'),
                ArrayValueTypedReader::getString($city, 'tag'),
                ArrayValueTypedReader::getInt($city, 'weight'),
                ArrayValueTypedReader::getString($city, 'country_name'),
                ArrayValueTypedReader::getString($city, 'region_name'),
            );

            if ($cityDto !== null) {
                $citiesDto[] = $cityDto;
            }
        }

        return $citiesDto;
    }
}