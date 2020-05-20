<?php

namespace App\Controller;

use App\Controller\Core\Core;

class ViewController extends Core
{
	public function index ()
	{
		return $this->render('index/index.html.twig');
	}
}
