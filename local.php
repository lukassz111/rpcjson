<?php
    require_once './include/ServerRPCjson.php';
    require_once 'func.php';

    $f = new Func();
    $server = new ServerRPCjson($f);
    echo $server->foo();
?>