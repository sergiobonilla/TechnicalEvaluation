<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Goal;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class GoalRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Goal::class);
	}

	public function findStrict (int $moment, Player $player, Game $game)
	{
		try {
			return $this->createQueryBuilder('g')
				->where('g.moment = :moment')
				->andWhere('g.player = :player')
				->andWhere('g.game = :game')
				->setParameter('moment', $moment)
				->setParameter('player', $player)
				->setParameter('game', $game)
				->getQuery()
				->getOneOrNullResult();
		} catch (NonUniqueResultException $e) {
			return null;
		}
	}
}
