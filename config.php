<?php

return [
	'requirements' => [
		'helpers/tools.php',
		'middleware/base.php',
		'helpers/mysql.php',
		'helpers/view.php'
	],
	'middleware' => [
		'basic_router'
	],
	'layout' => '/default',
	'mysql' => [
		'host' => 'localhost',
		'user' => 'test',
		'password' => 'testing123',
		'database' => 'test'
	]
];
