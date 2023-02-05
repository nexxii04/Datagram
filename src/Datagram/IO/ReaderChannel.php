<?php

declare(strict_types = 1);

namespace Datagram\IO;

use Threaded;

final class ReaderChannel 
{

	public function __construct(private Threaded $buffer) 
	{
	    
	}

	public function read() 
	{
		return $this->buffer->shift();
	}
}
