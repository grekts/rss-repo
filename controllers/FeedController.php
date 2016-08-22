<?php

namespace controllers;

use \lib\app\Maker;

class FeedController
{
    //Метод сохранения фида в БД
	public static function actionSave() {
	 	if((isset($_POST['feedUrl'])) && ($_POST['feedUrl'] !== '')) {
            //Фильтруем входные данные
	 		$feedUrl = Maker::$app -> filter($_POST['feedUrl']);
            //Проверяем является ли пришедшая строчка ссылкой
            $checkResult = Maker::$app -> checkUrlFormat($feedUrl);
            //Если пришедшая строка является ссылкой
            if($checkResult === '1') {
                $findResult = Maker::$app -> query('SELECT rss_url_list_id FROM rss_url_list WHERE rss_url = ?', [$feedUrl]);
                //Если фида в БД нет
	 			if($findResult === []) {
                    //Разделяем ссылку на домен и путь
	 				$devidedFeedUrl = Maker::$app -> devideUrl($feedUrl);
                    //Кодируем путь, если в ней есть русские буквы
	 				$encodingPuth = Maker::$app -> encodingPuth($devidedFeedUrl['path']);
                    //Кодируем домен, имеющий русские буквы
	 				$encodingDomain = Maker::$app -> encodingDomain($devidedFeedUrl['domain']);
                    //Формируем рабочую ссылку с протоколом передачи, www
	 				$fullLink = Maker::$app -> getFullLink($encodingDomain.$encodingPuth);
                    //Сохраняем фид в БД
	 				Maker::$app -> query('INSERT INTO rss_url_list VALUES (?, ?)', [null, $fullLink]);

                    echo 'Фид сохранен';
	 			} else {
	 				trigger_error('Указанные RSS фид уже находится в системе');
	 			}
	 		} else {
	 			trigger_error('Указанные данные не являются ссылкой');
	 		}
	 	} else {
	 		trigger_error('Не указана ссылка на RSS ленту');
	 	}
	}

    //Метод удаление фида
	public static function actionDelete() {
        if((isset($_POST['feedId'])) && ($_POST['feedId'] !== '')) {
            //Фильтруем входные данные
            $feedId = Maker::$app -> filter($_POST['feedId']);
            //Делаем запрос на удаление фида из БД
            Maker::$app -> query('DELETE FROM rss_url_list WHERE rss_url_list_id = ?', [(int)$feedId]);

            echo 'RSS лента удалена';
        } else {
            trigger_error('Не установлен или пустой идентификатор фида||0');
        }
    }

    //Метод парсинга фида
    public static function actionParse() {
        //Получаем список фидов
        $feedList = Maker::$app -> query('SELECT rss_url_list.rss_url, rss_url_list.rss_url_list_id FROM rss_url_list', []);
        //Получаем тайтлы новостей, находящихся в БД
        $titlesExistInDbNews = Maker::$app -> query('SELECT news.news_title FROM news', []);
        //Пробегаем список фидов
        foreach ($feedList as $feed) {
            //парсим RSS фид
        	$feedsCode = Maker::$app -> parseRss($feed['rss_url']);
            //Пробегаем каждую новости из фида
        	foreach ($feedsCode as $oneFeedCode) {
                //Устанавливаем начальное значение флага наличия новости в БД
        		$newsExistInDb = 0;
                //Разделяем описание на параграфы
        		$devidedDescrition = Maker::$app -> devideByTag($oneFeedCode['description'], 'br');
                //Удаляем html теги
        		$cleanDescription = Maker::$app -> deleteHtmlTags($devidedDescrition, ['img', 'iframe', 'b', 'p', 'i']);
                //Конвертируем внешние ссылки в нужный нам вид, добавляя nofollow b target="_blanck"
        		$cleanDescription = Maker::$app -> convertExternalUrls($cleanDescription, 'external-url');
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
        			Maker::$app -> query('INSERT INTO news VALUES (?, ?, ?, ?, ?, ?, ?)', [null, (int)$feed['rss_url_list_id'], $oneFeedCode['title'], $cleanDescription, $oneFeedCode['link'], $oneFeedCode['publicationDate'], 0]);
        		}
        	}
        }
    }
}