<?php

namespace Pingpong\Widget;

use Exception;

class WidgetException extends Exception
{
	protected $message;
	
	protected $error;

	protected $previous;

	public function __construct($message, $error = null, $previous = null) {

		$this->message 	= $message;
		$this->error 	= $error;
		$this->previous = $previous;

		parent::__construct($message, $error, $previous);

	}

	public function getMessages()
	{
		return $this->message;
	}

	public function getErrors()
	{
		return $this->error;
	}

	public function getCodes()
	{
		return $this->code;
	}

	public function getPreviouses()
	{
		return $this->previous;
	}

}
