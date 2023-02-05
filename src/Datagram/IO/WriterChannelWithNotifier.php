<?php

declare(strict_types = 1);

namespace Datagram\IO;

use pocketmine\snooze\SleeperNotifier;

use Threaded;

final class WriterChannelWithNotifier
{

	public function __construct(private Threaded $buffer, private SleeperNotifier $notifier)
	{
	    
	}

	public function write(string $buffer)
	{
		$this->buffer[] = $buffer;
		$this->notifier->wakeupSleeper();
		$this->notifier->wakeupSleeper();
	}
}
