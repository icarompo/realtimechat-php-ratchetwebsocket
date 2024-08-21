<?php

namespace Api\Websocket;

use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class SistemaChat implements MessageComponentInterface{
    protected $client;

    public function __construct() 
    {
        // Start the object that gon store the connected clients
        $this->client = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn) 
    {
        // Add client on a list
        $this->client->attach($conn);
        
        echo "New connection: {$conn->resourceId}\n\n";
    }

    // Send messages to connected clients
    public function onMessage(ConnectionInterface $from, $msg) 
    {
        foreach($this->client as $client) {
            // Not send the message to the user who send the message
            if($from !== $client) {
                $client->send($msg);
            }
        }
        echo "Usuário {$from->resourceId} enviou uma mensagem \n\n";
    }

    // Close the conection of the client with the websocket
    public function onClose(ConnectionInterface $conn) 
    {
        $this->client->detach($conn);

        echo "O Usuário {$conn->resourceId} desconectou .\n\n ";
    }

    //public function onError(){}
}