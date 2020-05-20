<?php

namespace App\Controller\Core;

use App\Entity\Game;
use App\Entity\Goal;
use App\Entity\Penalty;
use App\Entity\Player;
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

	/* ------------------- REPOS ACCESS ---------------------- */
	public function findGameStrict (string $location, $result, DateTime $date, array $teams) {
		return $this->em->getRepository(Game::class)->findStrict($location, $result, $date, $teams);
	}

	public function findAllGames () {
		return $this->em->getRepository(Game::class)->findAll();
	}

	public function findGame (string $location, DateTime $date) {
		return $this->em->getRepository(Game::class)->findGame($location, $date);
	}

	public function findPlayer (Team $team, string $name) {
		return $this->em->getRepository(Player::class)->findByTeamAndName($team, $name);
	}

	public function findTeamByName (string $name) {
		return $this->em->getRepository(Team::class)->findByName($name);
	}

	public function findGoalStrict (int $moment, Player $player, Game $game) {
		return $this->em->getRepository(Goal::class)->findStrict($moment, $player, $game);
	}

	public function findPenaltyStrict (int $moment, int $type, Player $player, Game $game) {
		return $this->em->getRepository(Penalty::class)->findStrict($moment, $type, $player, $game);
	}
	/* -------------------------------------------------------- */

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
		// Variables initialization
		$players  = array();
		$teams    = array();
		$location = $json['location'];
		$result   = (empty($json['result']) ? null : $json['result']);

		// Data validation
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

			$team = $this->createOrGetTeam($key);
			array_push($teams, $team);
			$players[$team->getId()] = isset($value['players']) ? $value['players'] : array();
		}

		$gameStrict = $this->findGameStrict($location, $result, $date, $teams);
		$game       = $this->findGame($location, $date);

		if (!empty($gameStrict) || !empty($game)) {
			$game->setResult($result);
			$game->resetTeams();
			$this->persist($game);

			foreach ($teams as $team) {
				$team->addGame($game);
				$game->addTeam($team);
				$this->persist($team);
				$this->persist($game);

				$this->setTeamPlayers($team, $game, $players[$team->getId()]);
			}
		} else {
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

				$this->setTeamPlayers($team, $game, $players[$team->getId()]);
			}

			return array(true, 'A new match has been created', $location, $result, $date, $teams);
		}
		return array(true, 'Json parsed and data registered', $location, $result, $date, $teams);
	}

	private function setTeamPlayers (Team $team, Game $game, array $players) {
		$team->resetPlayers();

		foreach ($players as $data) {
			$name      = $data['name'];
			$goals     = $data['goals'];
			$penalties = $data['penalty'];

			if (!$player = $this->findPlayer($team, $name)) {
				$player = new Player();
				$player->setName($name);
				$player->setTeam($team);

				$this->persist($player);
				$this->persist($team);
			}

			foreach ($goals as $goalData) {
				$moment = intval($goalData['moment']);

				if (!$goal = $this->findGoalStrict($moment, $player, $game)) {
					$goal = new Goal();
					$goal->setMoment($moment);
					$goal->setPlayer($player);
					$goal->setGame($game);

					$this->persist($goal);
					$this->persist($player);
					$this->persist($game);

					// Here the sms needs to be send
					$sms = '{"endpoints": "", "numer": "", "content": ""}';
				}
			}

			foreach ($penalties as $penaltyData) {
				$moment = intval($penaltyData['moment']);
				$type   = intval($penaltyData['type']);

				if (!$penalty = $this->findPenaltyStrict($moment, $type, $player, $game)) {
					$penalty = new Penalty();
					$penalty->setMoment($moment);
					$penalty->setType($type);
					$penalty->setPlayer($player);
					$penalty->setGame($game);

					$this->persist($penalty);
					$this->persist($player);
					$this->persist($game);
				}
			}
		}
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
