<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Team
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
	 * @ORM\OneToMany(targetEntity="Player", mappedBy="team", cascade={"remove"})
	 */
	private $players;

	/**
	 * @ORM\ManyToMany(targetEntity="Match", mappedBy="teams")
	 */
	private $matches;

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
		$this->created = new DateTime("now");
		$this->players = new ArrayCollection();
		$this->matches = new ArrayCollection();
	}

	public function getId () : int
	{
		return $this->id;
	}

	public function getName () : string
	{
		return $this->name;
	}

	public function setName (string  $name) : Team
	{
		$this->name = $name;
		return $this;
	}

	public function getPlayers () : ArrayCollection
	{
		return $this->players;
	}

	public function addPlayer (Player $player)
	{
		$this->getPlayers()->add($player);
	}

	public function getMatches () : ArrayCollection
	{
		return $this->matches;
	}

	public function addMatch (Match $match)
	{
		$this->getMatches()->add($match);
	}

	public function getCreated () : DateTime
	{
		return $this->created;
	}

	public function getUpdated () : DateTime
	{
		return $this->updated;
	}

	public function setUpdated (DateTime $updated) : Team
	{
		$this->updated = $updated;
		return $this;
	}
}
