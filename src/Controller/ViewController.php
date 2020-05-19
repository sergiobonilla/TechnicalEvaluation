<?php

namespace App\Controller;

class ViewController extends ParentController
{
	public function index ()
	{
		return $this->render('index/index.html.twig');
	}
}
