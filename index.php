<?php

include __DIR__.'/vendor/autoload.php';
include __DIR__.'/generated-conf/config.php';
include __DIR__.'/modules/setup.php';
include __DIR__.'/modules/server/Server.php';


$s = new Server();
$s->handleRequest()->body;
