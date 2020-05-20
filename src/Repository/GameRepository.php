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

	public function findByExact (string $location, $result, DateTime $date, array $teams)
	{
		if (count($teams) != 2)
			return null;

		try {
			$query = $this->createQueryBuilder('g')
				->join('g.teams', 'teams')
				->where('g.beginning = :beginning')
				->andWhere('g.location = :location');

			if (empty($result))
				$query->andWhere('g.result IS NULL');
			else {
				$query->andWhere('g.result = :result')
					->setParameter('result', $result);
			}

			return $query->andWhere('teams.name = :name1 OR teams.name = :name2 OR teams.name = :name2 OR teams.name = :name1')
				->setParameter('beginning', $date)
				->setParameter('location', $location)
				->setParameter('name1', $teams[0]->getName())
				->setParameter('name2', $teams[1]->getName())
				->getQuery()
				->getOneOrNullResult();
		} catch (NonUniqueResultException $e) {
			return null;
		}
	}
}