<?php

namespace liw\vendor\app\widgets\senderUrl\controllers;

class SenderUrlController
{
	//Метод сохранения ссылки
	public static function actionSaveUrl() {
	 	if((isset($_POST['data'])) && ($_POST['data'] !== '') && (gettype($_POST['data']) === 'string')) {
            //Фильтруем входные данные
	 		$url = \liw\vendor\app\Maker::$app -> filter($_POST['data']);
            //Проверяем является ли пришедшая строчка ссылкой
            $checkResult = \liw\vendor\app\Maker::$app -> checkUrlFormat($url);
            //Если пришедшая строка является ссылкой
            if($checkResult === '1') {
                $findResult = \liw\vendor\app\Maker::$app -> query('SELECT rss_url_list_id FROM rss_url_list WHERE rss_url = ?', [$url]);
                //Если ссылки в БД нет
	 			if($findResult === []) {
                    //Разделяем ссылку на домен и путь
	 				$devidedUrl = \liw\vendor\app\Maker::$app -> devideUrl($url);
                    //Кодируем путь, если в ней есть русские буквы
	 				$encodingPuth = \liw\vendor\app\Maker::$app -> encodingPuth($devidedUrl['path']);
                    //Кодируем домен, имеющий русские буквы
	 				$encodingDomain = \liw\vendor\app\Maker::$app -> encodingDomain($devidedUrl['domain']);
                    //Формируем рабочую ссылку с протоколом передачи, www
	 				$url = \liw\vendor\app\Maker::$app -> getFullLink($encodingDomain.$encodingPuth);
                    //Сохраняем ссылку в БД
	 				\liw\vendor\app\Maker::$app -> query('INSERT INTO rss_url_list VALUES (?, ?)', [null, $url]);

                    echo 'Ссылка сохранена';
	 			} else {
                    \liw\vendor\app\Maker::$app -> error('Указанная ссылка уже находится в системе', 1);
	 			}
	 		} else {
                \liw\vendor\app\Maker::$app -> error('Указанные данные не являются ссылкой', 1);
	 		}
	 	} else {
            \liw\vendor\app\Maker::$app -> error('Не указана ссылка', 1);
	 	}
	}
}