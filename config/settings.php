<?php
/**
 * Created by PhpStorm.
 * User: samuelblattner
 * Date: 18.08.18
 * Time: 16:19
 */

define("DEFAULT_PASSWORD_LENGTH", 16);

global $SETTINGS;

$SETTINGS['main-routes-file'] = __DIR__.'/routes.php';

$SETTINGS['frontend-entry-path'] = __DIR__.'/../../frontend/assets/dev/index.html';
$SETTINGS['assets-pattern'] = '/^\/assets\//';
$SETTINGS['assets-path'] = __DIR__.'/../../frontend/assets/';
$SETTINGS['debug'] = true;

if (file_exists(__DIR__.'/custom-settings.php')) {
	require_once(__DIR__.'/custom-settings.php');
}
