<?php

declare(strict_types=1);

namespace Component\City\Infrastructure\DependencyInjection\Repository;

use Component\City\Domain\Entity\City;
use Component\City\Domain\Repository\CityRepositoryInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<City>
 * @method City|null find(int $id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method City[] findBy(array $criteria, ?array $orderBy = null, int $limit = null, int $offset = null)
 *
 * @extends EntityRepository<City>
 */
class CityRepository extends EntityRepository implements CityRepositoryInterface
{
}
