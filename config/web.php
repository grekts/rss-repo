<?php

return [

	'db' => [
		'dbName' => 'database',
		'dbUserName' => 'user',
		'dbPass' => 'fghfgh',
		'dbHost' => 'localhost'
	],

	'linkMamager' => [
		'/' => 'site/index',
		'/archive' => 'site/archive',
		'/feed-list' => 'site/feed-list',
		'/save-feed' => 'feed/save',
		'/set-read' => 'news/set-read',
		'/news-to-archive' => 'news/send-to-archive',
		'/delete-from-archive' => 'news/delete-from-archive',
		'/delete-feed' => 'feed/delete'
	],

	'indexUrl' => 'Site/Index',
	'separator' => '|!|',
	'debug' => 'Off'

];