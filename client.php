<?php
    require_once './include/ClientRPCjson.php';
    $client = new ClientRPCjson("http://localhost/rpc/server.php");
    echo $client->foo();
?>