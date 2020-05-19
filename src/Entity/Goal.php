<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GoalRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Goal
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
	 * @ORM\ManyToOne(targetEntity="Player", inversedBy="goals")
	 * @ORM\JoinColumn(name="id_player", referencedColumnName="id", nullable=false)
	 */
	private $player;

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

	public function setMoment (int $moment) : Goal
	{
		$this->moment = $moment;
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

	public function getCreated () : DateTime
	{
		return $this->created;
	}

	public function getUpdated () : DateTime
	{
		return $this->updated;
	}

	public function setUpdated (DateTime $updated) : Goal
	{
		$this->updated = $updated;
		return $this;
	}
}
