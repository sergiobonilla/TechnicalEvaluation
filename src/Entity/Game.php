<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=100, unique=false)
	 */
	private $location;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $beginning;

	/**
	 * @ORM\Column(type="string", length=100, nullable=true)
	 */
	private $result;

	/**
	 * @ORM\ManyToMany(targetEntity="Team", inversedBy="games")
	 * @ORM\JoinTable(name="game_teams")
	 *
	 */
	private $teams;

	/**
	 * @ORM\OneToMany(targetEntity="Goal", mappedBy="game", cascade={"remove"})
	 */
	private $goals;

	/**
	 * @ORM\OneToMany(targetEntity="Penalty", mappedBy="game", cascade={"remove"})
	 */
	private $penalties;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $updated;

	public function __construct ()
	{
		$this->created   = new DateTime("now");
		$this->teams     = new ArrayCollection();
		$this->goals     = new ArrayCollection();
		$this->penalties = new ArrayCollection();
	}

	public function getId () : int
	{
		return $this->id;
	}

	public function getLocation () : string
	{
		return $this->location;
	}

	public function setLocation (string $location) : Game
	{
		$this->location = $location;
		return $this;
	}

	public function getBeginning () : DateTime
	{
		return $this->beginning;
	}

	public function setBeginning (DateTime $beginning) : Game
	{
		$this->beginning = $beginning;
		return $this;
	}

	public function getResult () : string
	{
		return $this->result;
	}

	public function setResult ($result) : Game
	{
		$this->result = $result;
		return $this;
	}

	public function getTeams ()
	{
		return $this->teams;
	}

	public function addTeam (Team $team)
	{
		$this->getTeams()->add($team);
	}

	public function hasTeam (Team $teamSearch) : bool
	{
		foreach ($this->getTeams() as $team) {
			if ($teamSearch->getId() === $team->getId())
				return true;
		}

		return false;
	}

	public function removeTeam (Team $team)
	{
		if ($this->hasTeam($team))
			$this->getTeams()->removeElement($team);
	}

	public function resetTeams ()
	{
		$this->teams = new ArrayCollection();
	}

	public function getGoals ()
	{
		return $this->goals;
	}

	public function addGoal (Goal $goal)
	{
		$this->getGoals()->add($goal);
	}

	public function getPenalties ()
	{
		return $this->penalties;
	}

	public function addPenalty (Penalty $penalty)
	{
		$this->getPenalties()->add($penalty);
	}

	public function getCreated () : DateTime
	{
		return $this->created;
	}

	public function getUpdated () : DateTime
	{
		return $this->updated;
	}

	public function setUpdated (DateTime $updated) : Game
	{
		$this->updated = $updated;
		return $this;
	}
}
