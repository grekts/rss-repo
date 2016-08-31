<?php

namespace lib\app\widgets\senderUrl\controllers;

use lib\app\Maker;

class SenderUrlController
{
	//Метод сохранения ссылки
	public static function actionSaveUrl() {
	 	if((isset($_POST['data'])) && ($_POST['data'] !== '') && (gettype($_POST['data']) === 'string')) {
            //Фильтруем входные данные
	 		$url = Maker::$app -> filter($_POST['data']);
            //Проверяем является ли пришедшая строчка ссылкой
            $checkResult = Maker::$app -> checkUrlFormat($url);
            //Если пришедшая строка является ссылкой
            if($checkResult === '1') {
                $findResult = Maker::$app -> query('SELECT rss_url_list_id FROM rss_url_list WHERE rss_url = ?', [$url]);
                //Если ссылки в БД нет
	 			if($findResult === []) {
                    //Разделяем ссылку на домен и путь
	 				$devidedUrl = Maker::$app -> devideUrl($url);
                    //Кодируем путь, если в ней есть русские буквы
	 				$encodingPuth = Maker::$app -> encodingPuth($devidedUrl['path']);
                    //Кодируем домен, имеющий русские буквы
	 				$encodingDomain = Maker::$app -> encodingDomain($devidedUrl['domain']);
                    //Формируем рабочую ссылку с протоколом передачи, www
	 				$url = Maker::$app -> getFullLink($encodingDomain.$encodingPuth);
                    //Сохраняем ссылку в БД
	 				Maker::$app -> query('INSERT INTO rss_url_list VALUES (?, ?)', [null, $url]);

                    echo 'Ссылка сохранена';
	 			} else {
                    Maker::$app -> error('Указанная ссылка уже находится в системе', 1);
	 			}
	 		} else {
                Maker::$app -> error('Указанные данные не являются ссылкой', 1);
	 		}
	 	} else {
            Maker::$app -> error('Не указана ссылка', 1);
	 	}
	}
}