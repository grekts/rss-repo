<?php

namespace controllers;

use \lib\app\Maker;

class NewsController
{
	//Метод устанавливает что новость прочитана
	public static function actionSetRead() {
		if((isset($_POST['newsId'])) && ($_POST['newsId'] !== '')) {
			$cleanNewsId = Maker::$app -> filter($_POST['newsId']);
			Maker::$app -> query('UPDATE news SET news.read = ? WHERE news_id = ? LIMIT 1', [1, (int)$cleanNewsId]);
		} else {
			trigger_error('Не установлен или пустой идентификатор новости||0');
		}
	}

	//Метод удаления прочитанных новостей
	public static function actionDelete() {
		Maker::$app -> query('DELETE FROM news WHERE news.read = ?', [1]);
	}

	//Метод переноса новости в архив
	public static function actionSendToArchive() {
		if((isset($_POST['newsId'])) && ($_POST['newsId'] !== '')) {
			$cleanNewsId = Maker::$app -> filter($_POST['newsId']);
			//Получаем данные новости, которая будет отправлено в архив
			$newsData = Maker::$app -> query('SELECT news.news_title, news.news_description, news.news_link, news.publication_date, news.rss_url_list_id FROM news WHERE news.news_id = ?', [(int)$cleanNewsId]);

			//Отправляем новость в архив
			Maker::$app -> query('INSERT INTO news_archive VALUES (?, ?, ?, ?, ?, ?)', [NULL, (int)$newsData[0]['rss_url_list_id'], $newsData[0]['news_title'], $newsData[0]['news_description'], $newsData[0]['news_link'], $newsData[0]['publication_date']]);
		} else {
			trigger_error('Не установлен или пустой идентификатор новости||0');
		}
	}

	//Метод удаления новости из архива
	public static function actionDeleteFromArchive() {
		if((isset($_POST['newsId'])) && ($_POST['newsId'] !== '')) {
			$cleanNewsId = Maker::$app -> filter($_POST['newsId']);
			Maker::$app -> query('DELETE FROM news_archive WHERE news_archive.news_archive_id = ?', [(int)$cleanNewsId]);
		} else {
			trigger_error('Не установлен или пустой идентификатор новости||0');
		}
	}
}