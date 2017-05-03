<?php
//error_reporting(E_ALL ^ E_NOTICE);
define(_CORE_PATH, 'core/');
define(_APP_PATH, 'app/');
/**
 * load all you need
 */
include_once(_CORE_PATH . 'model.php');

include_once(_CORE_PATH . 'request.php');

include_once(_CORE_PATH . 'controller.php');
 
include_once(_CORE_PATH . 'v429.php');

include_once(_CORE_PATH . 'view.php');
