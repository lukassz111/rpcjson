<?php
    require_once './include/ServerRPCjson.php';
    require_once 'func.php';

    $f = new Func();
    $server = new ServerRPCjson($f);
    $server->setVar("asdas");
    echo $server->getVar();
    echo '<hr>';
    echo serialize($f);
?>