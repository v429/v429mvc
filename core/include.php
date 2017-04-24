<?php
//error_reporting(E_ALL ^ E_NOTICE);
/**
 * load all you need
 */
$GLOBALS['config'] = include('app/config.php');

include_once('core/model.php');

include_once('core/request.php');

include_once('core/controller.php');
 
include_once('core/v429.php');

include_once('core/view.php');
