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
	 * @ORM\OneToMany(targetEntity="Player", mappedBy="team", cascade={"remove"})
	 */
	private $players;

	/**
	 * @ORM\ManyToOne(targetEntity="Match", inversedBy="teams")
	 * @ORM\JoinColumn(name="id_match", referencedColumnName="id", nullable=false)
	 */
	private $match;

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
	}

	public function getId () : int
	{
		return $this->id;
	}

	public function getPlayers () : ArrayCollection
	{
		return $this->players;
	}

	public function addPlayer (Player $player)
	{
		$this->getPlayers()->add($player);
	}

	public function getMatch () : Match
	{
		return $this->match;
	}

	public function setMatch (Match $match)
	{
		$this->match = $match;
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