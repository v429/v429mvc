<?php
/****
 * composer psr autoload
 **/
require __DIR__."/vendor/autoload.php";

/***
 * main v429 engine
 */
use Core\V429;

/**
 * start request
 * @var v429
 */
$app = new v429();

$app->run();
