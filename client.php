<?php
    require_once './include/ClientRPCjson.php';
    if(file_exists('s.txt'))
    {
        $serialized = file_get_contents('s.txt');
        $client = unserialize($serialized);
    }
    //$client = new ClientRPCjson("http://localhost/rpc/server.php",true);
    echo $client->foo().'<br>';
    file_put_contents('s.txt',serialize($client));
    echo $client->setVar("WAR1").'<br>';
    echo $client->getVar().'<br>';
?>