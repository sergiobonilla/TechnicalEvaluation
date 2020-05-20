<?php

namespace App\Controller\Core;

use App\Entity\Game;
use App\Entity\Team;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Core extends AbstractController
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function getGame (string $location, DateTime $date, array $teams) {
		return $this->em->getRepository(Game::class)->findByExact($location, $date, $teams);
	}

	public function findTeamByName (string $name) {
		return $this->em->getRepository(Team::class)->findByName($name);
	}

	protected function createOrGetTeam ($name) {
		$team = $this->findTeamByName($name);

		if (empty($team)) {
			$team = new Team();
			$team->setName($name);
			$this->persist($team);
		}

		return $team;
	}

	protected function processJsonGame ($json) : array
	{
		$teams    = array();
		$location = $json['location'];
		$result   = (empty($json['result']) ? null : $json['result']);

		try {
			$date = new DateTime(date("Y-m-d H:i:s", strtotime($json['date'])));
		} catch (Exception $e) {
			return array(false, 'Error parsing a date', $location, $result, null, $teams);
		}

		if (count($json['teams']) != 2)
			return array(false, 'Just 2 teams available', $location, $result, $date, $teams);

		if (strlen($location) >= 100)
			return array(false, 'Location is too long', $location, $result, $date, $teams);

		if (!empty($result) && count(explode( '|', $result )) != 2)
			return array(false, 'The result does not look as it should', $location, $result, $date, $teams);

		foreach ($json['teams'] as $key => $value) {
			if (strlen($key) >= 100)
				return array(false, "$key is too long", $location, $result, $date, $teams);

			array_push($teams, $this->createOrGetTeam($key));
		}

		$game = $this->getGame($location, $date, $teams);

		if (empty($game)) {
			// Let's create the game because it has been not found
			$game = new Game();
			$game->setLocation($location);
			$game->setBeginning($date);
			$game->setResult($result);
			$this->persist($game);

			foreach ($teams as $team) {
				$team->addGame($game);
				$game->addTeam($team);
				$this->persist($team);
				$this->persist($game);
			}
		}

		return array(true, 'Json parsed and data registered', $location, $result, $date, $teams);
	}


	private function persist ($entity) {
		$this->em->persist($entity);
		$this->em->flush();
	}

	private function remove ($entity) {
		$this->em->remove($entity);
		$this->em->flush();
	}
}
