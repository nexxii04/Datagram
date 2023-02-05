<?php

declare(strict_types = 1);

namespace Datagram\Network;

use Datagram\Network\Handler;
use Datagram\IO\ReaderChannel;
use Datagram\IO\WriterChannelWithNotifier;

use pocketmine\snooze\SleeperNotifier;
use pocketmine\thread\Thread;
use raklib\generic\Socket;
use raklib\utils\InternetAddress;

use Threaded;

class Network extends Thread
{

	/** @var InternetAddress $address */
	private $address;

	/** @var SleeperNotifier */
	private $sleeper;

	/** @var Threaded */
	private $threadToMain;

	/** @var Threaded */
	private $mainToThread;

	public function __construct(InternetAddress $address, SleeperNotifier $sleeper, Threaded $mainToThread, Threaded $threadToMain) 
	{
		$this->address = $address;
		$this->sleeper = $sleeper;
		$this->mainToThread = $mainToThread;
		$this->threadToMain = $threadToMain;
	}

	public function stop() 
	{
	    
	}

	public function onRun(): void 
	{
		try {
			$socket = new Socket($this->address);
			echo "Datagram thread/info: listening " . $this->address->toString() . PHP_EOL;


			$channelReader = new ReaderChannel($this->mainToThread);
			$channelWriter = new WriterChannelWithNotifier($this->threadToMain, $this->sleeper);

			while ($this->isRunning()) {
				Handler::receiveData($socket, $channelWriter);
				Handler::sendData($socket, $channelReader);
			}
		} catch (Exception $e) {
			echo PHP_EOL . $e->getMessage() . PHP_EOL;
			exit(1);
		}
	}
}
