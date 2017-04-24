<?php

return [
	'/'          => ['indexController', 'index'],
	'index'  => ['indexController'],
	'test'     => ['testController'],
	'admin' => ['adminController'],
	'front'   => ['frontController'],
	'login' => ['userController', 'login'],
	'front/login' => ['frontController', 'login']
];