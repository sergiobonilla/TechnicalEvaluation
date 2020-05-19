<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Player
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
	private $name;

	/**
	 * @ORM\OneToMany(targetEntity="Goal", mappedBy="player", cascade={"remove"})
	 */
	private $goals;

	/**
	 * @ORM\OneToMany(targetEntity="Penalty", mappedBy="player", cascade={"remove"})
	 */
	private $penalties;

	/**
	 * @ORM\ManyToOne(targetEntity="Team", inversedBy="players")
	 * @ORM\JoinColumn(name="id_team", referencedColumnName="id", nullable=false)
	 */
	private $team;

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
		$this->goals     = new ArrayCollection();
		$this->penalties = new ArrayCollection();
	}

	public function getId () : int
	{
		return $this->id;
	}

	public function getName () : string
	{
		return $this->name;
	}

	public function setName (string  $name) : Player
	{
		$this->name = $name;
		return $this;
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

	public function getTeam () : Team
	{
		return $this->team;
	}

	public function setTeam (Team $team)
	{
		$this->team = $team;
	}

	public function getCreated () : DateTime
	{
		return $this->created;
	}

	public function getUpdated () : DateTime
	{
		return $this->updated;
	}

	public function setUpdated (DateTime $updated) : Player
	{
		$this->updated = $updated;
		return $this;
	}
}
