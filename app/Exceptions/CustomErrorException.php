<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CustomErrorException extends Exception
{
	protected $status_code;
	
	public function __construct($message = "", $code = 0, $status_code = 400, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
		
		$this->status_code = $status_code;
	}
	
	public function getStatusCode()
	{
		return $this->status_code;
	}
}