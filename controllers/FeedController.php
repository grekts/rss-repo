<?php

namespace liw\controllers;

/**
 * Контроллер управления RSS фидами.
 * Контроллер предоставляет функциональность для удаления RSS фидов из базы данных сервиса,
 * а так же для парсинга фидов, сохраненных в сервисе.
 * 
 * @author Roman Tsutskov
 */
class FeedController
{
    /**
     * Метод удаления фида из базы данных
     * 
     * @return string Сообщение о том, что фид удален
    */
	public static function actionDelete() {
        $dataType = gettype($_POST['feedId']);
        if((isset($_POST['feedId'])) && ($_POST['feedId'] !== '') && ($dataType === 'string')) {
            //Фильтруем входные данные
            $feedId = \liw\vendor\app\Maker::$app -> filter($_POST['feedId']);
            //Делаем запрос на удаление фида из БД
            \liw\vendor\app\Maker::$app -> query('DELETE FROM rss_url_list WHERE rss_url_list_id = ?', [(int)$feedId]);

            echo 'RSS лента удалена';
        } else {
            if($dataType !== 'string') {
                \liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип id фида не является string');
            }
            if(($_POST['feedId'] === '') || (isset($_POST['feedId']) === false)) {
                \liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не определен id фида');
            }
        }
    }

    /**
     * Метод париснга фидов и сохранения новостей в базе данных
    */
    public static function actionParse() {
        //Получаем список фидов
        $feedList = \liw\vendor\app\Maker::$app -> query('SELECT rss_url_list.rss_url, rss_url_list.rss_url_list_id FROM rss_url_list', []);
        //Получаем тайтлы новостей, находящихся в БД
        $titlesExistInDbNews = \liw\vendor\app\Maker::$app -> query('SELECT news.news_title FROM news', []);
        //Пробегаем список фидов
        foreach ($feedList as $feed) {
            //парсим RSS фид
        	$feedsCode = \liw\vendor\app\Maker::$app -> parseRss($feed['rss_url']);
            //Пробегаем каждую новости из фида
        	foreach ($feedsCode as $oneFeedCode) {
                //Устанавливаем начальное значение флага наличия новости в БД
        		$newsExistInDb = 0;
                //Разделяем описание на параграфы
        		$devidedDescrition = \liw\vendor\app\Maker::$app -> devideByTag($oneFeedCode['description'], 'br');
                //Удаляем html теги
        		$cleanDescription = \liw\vendor\app\Maker::$app -> deleteHtmlTags($devidedDescrition, ['img', 'iframe', 'b', 'p', 'i']);
                //Конвертируем внешние ссылки в нужный нам вид, добавляя nofollow b target="_blanck"
        		$cleanDescription = \liw\vendor\app\Maker::$app -> convertExternalUrls($cleanDescription, 'external-url');
                //Пробегаем список заголовков новостей, находящихся в БД
        		foreach ($titlesExistInDbNews as $titleOneExistInDbNews) {
                    //Если заголовок текущей обрабатываемой новой новости совпадает с заголовком новости из БД
        			if($titleOneExistInDbNews['news_title'] === $oneFeedCode['title']) {
                        //Устанавливаем флаг что новость есть в БД
        				$newsExistInDb = 1;
        				break;
        			}
        		}
                //Если новости нет в БД
        		if($newsExistInDb === 0) {
        			\liw\vendor\app\Maker::$app -> query('INSERT INTO news VALUES (?, ?, ?, ?, ?, ?, ?)', [null, (int)$feed['rss_url_list_id'], $oneFeedCode['title'], $cleanDescription, $oneFeedCode['link'], $oneFeedCode['publicationDate'], 0]);
        		}
        	}
        }
    }
}