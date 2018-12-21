<?php


/**
 * Routes to API-Endpoints.
 */

$ROUTES = [
	["/^\/?auth\//", null, __DIR__.'/../modules/auth/routes.php'],
	["/^\/?users\//", null, __DIR__.'/../modules/users/routes.php'],
	["/^\/?missioncontrol\//", null, __DIR__.'/../modules/missioncontrol/routes.php'],
	["/^\/?drslumber\//", null, __DIR__.'/../modules/drslumber/routes.php']
];
