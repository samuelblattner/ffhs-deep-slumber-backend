<?php

include __DIR__.'/vendor/autoload.php';
include __DIR__.'/generated-conf/config.php';
include __DIR__.'/modules/setup.php';
include __DIR__.'/modules/server/Server.php';


/**
 * === MAIN ENTRY POINT ===
 *
 * This is the PHP entry point, i.e. the first file loaded by the Webserver (NginX).
 * Every request creates a Server-instance that will coordinate the request/response-cycle.
 */
$s = new Server();
$s->handleRequest();
