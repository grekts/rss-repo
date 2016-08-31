<?php

namespace controllers;

use \lib\app\Maker;

class SiteController
{
	public static function actionIndex() {
		Maker::$app -> tagRegistration([
			'title' => 'Новости',
			'meta' => [
				[
					'charset' => 'utf-8'
				],
				[
					'name' => 'viewport',
					'content' => 'width=device-width, initial-scale=1.0'
				],
				[
					'name' => 'description', 
					'content' => 'Список новостей'
				]
			],
			'link' => [
				[
					'rel' => 'stylesheet', 
					'href' => 'web/style/css/style.css'
				]
			],
			'script' => [
				[
					'type' => 'text/javascript',
					'src' => 'https://code.jquery.com/jquery-3.1.0.min.js'
				],
				[
					'type' => 'text/javascript',
					'src' => 'web/js/index.js'
				]
			]
		]);

		Maker::$app -> widgetRegistration([
			'menu' => [
				'content' => ['Список лент', 'Архив'],
				'url' => ['feed-list', 'archive']
			],
			'senderUrl' => [
				'startValue' => 'Ссылка',
				'buttonName' => 'Добавить'
			]
		]);

		$newsList = Maker::$app -> query('SELECT news.news_id, news.news_title, news.news_description, news.news_link, news.publication_date FROM news WHERE news.read = ?', [0]);
		Maker::$app -> render('index', ['newsList' => $newsList]);
	}

	public static function actionArchive() {
		Maker::$app -> tagRegistration([
			'title' => 'Архив новостей',
			'meta' => [
				[
					'charset' => 'utf-8'
				],
				[
					'name' => 'viewport',
					'content' => 'width=device-width, initial-scale=1.0'
				],
				[
					'name' => 'description', 
					'content' => 'Архив новостей'
				]
			],
			'link' => [
				[
					'rel' => 'stylesheet', 
					'href' => 'web/style/css/style.css'
				]
			],
			'script' => [
				[
					'type' => 'text/javascript',
					'src' => 'https://code.jquery.com/jquery-3.1.0.min.js'
				],
				[
					'type' => 'text/javascript',
					'src' => 'web/js/index.js'
				]
			]
		]);

		Maker::$app -> widgetRegistration([
			'menu' => [
				'content' => ['Список лент', 'Архив'],
				'url' => ['feed-list', 'archive']
			],
			'senderUrl' => [
				'startValue' => 'Ссылка',
				'buttonName' => 'Добавить'
			]
		]);

		$newsList = Maker::$app -> query('SELECT news_archive.news_title, news_archive.news_description, news_archive.news_link, news_archive.publication_date, news_archive.news_archive_id
      FROM news_archive', []);
		Maker::$app -> render('archive', ['newsList' => $newsList]);
	}

	public static function actionFeedList() {
		Maker::$app -> tagRegistration([
			'title' => 'RSS ленты',
			'meta' => [
				[
					'charset' => 'utf-8'
				],
				[
					'name' => 'viewport',
					'content' => 'width=device-width, initial-scale=1.0'
				],
				[
					'name' => 'description', 
					'content' => 'Список RSS лент'
				]
			],
			'link' => [
				[
					'rel' => 'stylesheet', 
					'href' => 'web/style/css/style.css'
				]
			],
			'script' => [
				[
					'type' => 'text/javascript',
					'src' => 'https://code.jquery.com/jquery-3.1.0.min.js'
				],
				[
					'type' => 'text/javascript',
					'src' => 'web/js/index.js'
				]
			]
		]);

		Maker::$app -> widgetRegistration([
			'menu' => [
				'content' => ['Список лент', 'Архив'],
				'url' => ['feed-list', 'archive']
			],
			'senderUrl' => [
				'startValue' => 'Ссылка',
				'buttonName' => 'Добавить'
			]
		]);

		$feedsList = Maker::$app -> query('SELECT rss_url_list.rss_url, rss_url_list.rss_url_list_id FROM rss_url_list', []);
		Maker::$app -> render('feed-list', ['feedsList' => $feedsList]);
	}
}