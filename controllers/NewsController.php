<?php

namespace liw\controllers;

/**
 * Контроллер управления новостями, полученными из фидов.
 * Контроллер предоставляет функциональность для удаления прочитанных новостей, 
 * определения, что новость прчитана, отправик новостей в архив,
 * удаления новостей из архива.
 * 
 * @author Roman Tsutskov
 */
class NewsController
{
	/**
	 * Метод устанавливает что новость прочитана
	 */
	public static function actionSetRead() {
		$dataType = gettype($_POST['newsId']);
		if((isset($_POST['newsId'])) && ($_POST['newsId'] !== '') && ($dataType === 'string')) {
			$cleanNewsId = \liw\vendor\app\Maker::$app -> filter($_POST['newsId']);
			\liw\vendor\app\Maker::$app -> query('UPDATE news SET news.read = ? WHERE news_id = ? LIMIT 1', [1, (int)$cleanNewsId]);
		} else {
			if($dataType !== 'string') {
                \liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип id новости не является string');
            }
            if(($_POST['newsId'] === '') || (isset($_POST['newsId']) === false)) {
                \liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не определен id новости');
            }
		}
	}

	/**
	 * Метод удаления прочитанных новостей
	 */
	public static function actionDelete() {
		\liw\vendor\app\Maker::$app -> query('DELETE FROM news WHERE news.read = ?', [1]);
	}

	/**
	 * Метод переноса новости в архив
	 */
	public static function actionSendToArchive() {
		$dataType = gettype($_POST['newsId']);
		if((isset($_POST['newsId'])) && ($_POST['newsId'] !== '') && ($dataType === 'string')) {
			$cleanNewsId = \liw\vendor\app\Maker::$app -> filter($_POST['newsId']);
			//Получаем данные новости, которая будет отправлено в архив
			$newsData = \liw\vendor\app\Maker::$app -> query('SELECT news.news_title, news.news_description, news.news_link, news.publication_date, news.rss_url_list_id FROM news WHERE news.news_id = ?', [(int)$cleanNewsId]);

			//Отправляем новость в архив
			\liw\vendor\app\Maker::$app -> query('INSERT INTO news_archive VALUES (?, ?, ?, ?, ?, ?)', [NULL, (int)$newsData[0]['rss_url_list_id'], $newsData[0]['news_title'], $newsData[0]['news_description'], $newsData[0]['news_link'], $newsData[0]['publication_date']]);
		} else {
			if($dataType !== 'string') {
                \liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип id новости не является string');
            }
            if(($_POST['newsId'] === '') || (isset($_POST['newsId']) === false)) {
                \liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не определен id новости');
            }
		}
	}

	/**
	 * Метод удаления новости из архива
	 */
	public static function actionDeleteFromArchive() {
		$dataType = gettype($_POST['newsId']);
		if((isset($_POST['newsId'])) && ($_POST['newsId'] !== '') && ($dataType === 'string')) {
			$cleanNewsId = \liw\vendor\app\Maker::$app -> filter($_POST['newsId']);
			\liw\vendor\app\Maker::$app -> query('DELETE FROM news_archive WHERE news_archive.news_archive_id = ?', [(int)$cleanNewsId]);
		} else {
			if($dataType !== 'string') {
                \liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип id новости не является string');
            }
            if(($_POST['newsId'] === '') || (isset($_POST['newsId']) === false)) {
                \liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не определен id новости');
            }
		}
	}
}