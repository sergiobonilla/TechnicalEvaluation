<?php

namespace App\Repository;

use DateTime;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class GameRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Game::class);
	}

	public function findGame (string $location, DateTime $date)
	{
		try {
			return $this->createQueryBuilder('g')
				->join('g.teams', 'teams')
				->where('g.beginning = :beginning')
				->andWhere('g.location = :location')
				->setParameter('beginning', $date)
				->setParameter('location', $location)
				->getQuery()
				->getOneOrNullResult();
		} catch (NonUniqueResultException $e) {
			return null;
		}
	}

	public function findStrict (string $location, $result, DateTime $date, array $teams)
	{
		try {
			$query = $this->createQueryBuilder('g')
				->join('g.teams', 'teams')
				->join('g.teams', 'teams1')
				->where('g.beginning = :beginning')
				->andWhere('g.location = :location');

			if (empty($result))
				$query->andWhere('g.result IS NULL');
			else {
				$query->andWhere('g.result = :result')
					->setParameter('result', $result);
			}

			return $query->andWhere('teams.id = :id1 AND teams1.id = :id2 OR teams1.id = :id1 AND teams.id = :id2')
				->setParameter('beginning', $date)
				->setParameter('location', $location)
				->setParameter('id1', $teams[0]->getId())
				->setParameter('id2', $teams[1]->getId())
				->getQuery()
				->getOneOrNullResult();
		} catch (NonUniqueResultException $e) {
			return null;
		}
	}
}
