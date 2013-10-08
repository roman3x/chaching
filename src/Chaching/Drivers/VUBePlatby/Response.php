<?php

/*
 * This file is part of Chaching.
 *
 * (c) 2013 BACKBONE, s.r.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chaching\Drivers\VUBePlatby;

use \Chaching\Driver;
use \Chaching\Currencies;
use \Chaching\Exceptions\InvalidOptionsException;

final class Response extends \Chaching\Messages\Hmac
{
	public $status 				= FALSE;
	public $variable_symbol 	= NULL;

	public function __construct(Array $authorization, Array $options)
	{
		parent::__construct();

		$this->readonly_fields = array(
			'RES', 'SIGN', 'VS', 'SS'
		);

		$this->fields = array(
			'RES' 	=> isset($options['RES']) ? $options['RES'] : NULL,
			'SIGN' 	=> isset($options['SIGN']) ? $options['SIGN'] : NULL,
			'VS' 	=> isset($options['VS']) ? $options['VS'] : NULL,
			'SS' 	=> isset($options['SS']) ? $options['SS'] : NULL
		);

		$this->set_authorization($authorization);

		$this->validate();
	}

	/**
	 * @return 	bool
	 * @throw 	\Chaching\Exceptions\InvalidResponseException
	 */
	protected function validate()
	{
		$signature = $this->sign($this->signature_base());

		if ($this->fields['SIGN'] !== $signature)
			throw new \Chaching\Exceptions\InvalidResponseException(sprintf(
				"Signature received as part of the response is incorrect (" .
				"'%s' expected, got '%s'). If this persists contact the bank.",
				$signature, $this->fields['SIGN']
			));

		$this->fields['RES'] = strtolower($this->fields['RES']);

		$this->variable_symbol 	= $this->fields['VS'];
		$this->status 			= ($this->fields['RES'] === 'ok' AND !empty($this->fields['VS']))
			? \Chaching\Statuses::SUCCESS
			: \Chaching\Statuses::FAILURE;

		return $this->status;
	}

	protected function signature_base()
	{
		return $this->fields['VS'] . $this->fields['SS'] . $this->fields['RES'];
	}
}