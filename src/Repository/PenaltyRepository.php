<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Penalty;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class PenaltyRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Penalty::class);
	}

	public function findStrict (int $moment, int $type,  Player $player, Game $game)
	{
		try {
			return $this->createQueryBuilder('p')
				->where('p.moment = :moment')
				->andWhere('p.type = :type')
				->andWhere('p.player = :player')
				->andWhere('p.game = :game')
				->setParameter('moment', $moment)
				->setParameter('type', $type)
				->setParameter('player', $player)
				->setParameter('game', $game)
				->getQuery()
				->getOneOrNullResult();
		} catch (NonUniqueResultException $e) {
			return null;
		}
	}
}
