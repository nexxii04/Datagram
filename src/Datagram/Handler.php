<?php

declare(strict_types = 1);

namespace Datagram;

use Datagram\IO\ReaderChannel;

final class Handler
{

    public function __construct(private ReaderChannel $channel, private Datagram $datagram) {
        
    }

    public function handle(): bool 
    {
        if (($data = $this->channel->read()) === null) {
            return false;
        }

        $content = explode('%20', $data);
        if (count($content) < 2) {
            return true;
        }

        // ip + port
        $address = explode(':', $content[0]);

        // buffer, ip and port
        $this->datagram->emit('data', [$content[1], $address[0], $address[1]]);
        return true;
    }
}