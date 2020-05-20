<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PenaltyRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Penalty
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $moment;

	/**
	 * @ORM\Column(type="boolean")
	 *
	 * TRUE will  means red
	 * FALSE will means yellow
	 */
	private $type;

	/**
	 * @ORM\ManyToOne(targetEntity="Player", inversedBy="penalties")
	 * @ORM\JoinColumn(name="id_player", referencedColumnName="id", nullable=false)
	 */
	private $player;

	/**
	 * @ORM\ManyToOne(targetEntity="Game", inversedBy="penalties")
	 * @ORM\JoinColumn(name="id_game", referencedColumnName="id", nullable=false)
	 */
	private $game;

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
	}

	public function getId () : int
	{
		return $this->id;
	}

	public function getMoment () : int
	{
		return $this->moment;
	}

	public function setMoment (int  $moment) : Penalty
	{
		$this->moment = $moment;
		return $this;
	}

	public function getType () : bool
	{
		return $this->type;
	}

	public function setType (int $type) : Penalty
	{
		$this->type = $type;
		return $this;
	}

	public function getPlayer () : Player
	{
		return $this->player;
	}

	public function setPlayer (Player $player)
	{
		$this->player = $player;
	}

	public function getGame () : Game
	{
		return $this->game;
	}

	public function setGame (Game $game)
	{
		$this->game = $game;
	}

	public function getCreated () : DateTime
	{
		return $this->created;
	}

	public function getUpdated () : DateTime
	{
		return $this->updated;
	}

	public function setUpdated (DateTime $updated) : Penalty
	{
		$this->updated = $updated;
		return $this;
	}
}
