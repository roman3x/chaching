<?php

/*
 * This file is part of Chaching.
 *
 * (c) 2013 BACKBONE, s.r.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chaching\Drivers;

class TBTatraPay extends \Chaching\Driver
{
	public function request(Array $options)
	{
		static $request = NULL;

		if ($request === NULL)
		{
			$request = new \Chaching\Drivers\TBTatraPay\Request(
				$this->authorization, $options
			);
		}

		return $request;
	}

	public function response(Array $options)
	{
		static $response = NULL;

		if ($response === NULL)
		{
			$response = new \Chaching\Drivers\TBTatraPay\Response(
				$this->authorization, $options
			);
		}

		return $response;
	}
}