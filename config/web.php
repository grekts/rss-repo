<?php

/**
 * Пользовательский конфигурационный файл
 */

return [

	'db' => [
		'dbName' => '',
		'dbUserName' => '',
		'dbPass' => '',
		'dbHost' => 'localhost'
	],

	'linkMamager' => [
		'/' => 'site/index',
		'/archive' => 'site/archive',
		'/feed-list' => 'site/feed-list',
		'/set-read' => 'news/set-read',
		'/news-to-archive' => 'news/send-to-archive',
		'/delete-from-archive' => 'news/delete-from-archive',
		'/delete-feed' => 'feed/delete'
	],

	'indexUrl' => 'site/index',
	'separator' => '|!|'
];