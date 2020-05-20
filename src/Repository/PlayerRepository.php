<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class PlayerRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Player::class);
	}

	public function findByTeamAndName (Team $team, string $name)
	{
		try {
			return $this->createQueryBuilder('p')
				->where('p.team = :team')
				->andWhere('p.name = :name')
				->setParameter('team', $team)
				->setParameter('name', $name)
				->getQuery()
				->getOneOrNullResult();
		} catch (NonUniqueResultException $e) {
			return null;
		}
	}
}
