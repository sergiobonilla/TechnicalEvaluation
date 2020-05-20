<?php

namespace App\Controller;

use App\Controller\Core\Core;

class ViewController extends Core
{
	public function index ()
	{
		$games = $this->findAllGames();

		return $this->render('index/index.html.twig', array('games' => $games));
	}
}
