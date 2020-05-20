<?php

namespace App\Controller;

use App\Controller\Core\Core;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Core
{
	public function pushData (Request $request)
	{
		/**
		 * TODO for better performance of the function here should be implemented a token authentication for the
		 * request, but for dev purpose I will not do
		 */
		$data = '';

		if ($parsed = json_decode($data, true)) {
			if (isset($parsed['club'])) {
				foreach ($parsed['club'] as $game) {
					list($status, $message, $location, $result, $date, $teams) = $this->processJsonGame($game);

					if (!$status)
						return new JsonResponse(array('message' => $message), Response::HTTP_BAD_REQUEST);
				}
				return new JsonResponse(array('message' => 'Data registered successfully'), Response::HTTP_OK);
			}
		} else
			return new JsonResponse(array('message' => 'Bad formed json data'), Response::HTTP_BAD_REQUEST);
	}
}
