<?php

namespace liw\vendor\app\modules;

class Parser
{
	//Метод парсинга RSS фида
	public function parseRss($url) {
		$dataType = gettype($url);
		if(($dataType === 'string') && ($url !== '')) {
			$domDoc = new \DOMDocument;
			//Загружаем фид
			$loadFlag = $domDoc->load($url);
			//Если не произошла ошибка при получении контента фида
			if($loadFlag === true) {
				//Получаем списки новостей
				$items = $domDoc->getElementsByTagName('item');
				//Пробегаем каждую новость
				foreach($items as $codeOneItem) {
					//Получаем title
			        $newsTitleObject = $codeOneItem->getElementsByTagName('title');
			        $titleText = $newsTitleObject->item(0)->nodeValue;
			        $title = htmlspecialchars($titleText);
			        //Получаем description
			        $newsDescriptionObject = $codeOneItem->getElementsByTagName('description');
			        $description = htmlspecialchars($newsDescriptionObject->item(0)->nodeValue);
			        //Получаем ссылку на новость
			        $newsLinkObject = $codeOneItem->getElementsByTagName('link');
			        $link = htmlspecialchars($newsLinkObject->item(0)->nodeValue);
			        //Получаем дату публикации новости
			        $newsDateObject = $codeOneItem->getElementsByTagName('pubDate');
			        $publicationDate = htmlspecialchars($newsDateObject->item(0)->nodeValue);
			        $explodeDate = explode(' ', trim($publicationDate));
			        $day = (int)$explodeDate[1];
			        switch($explodeDate[2]) {
						case 'Jan': $month = 1; break;
						case 'Feb': $month = 2; break;
						case 'Mar': $month = 3; break;
						case 'Apr': $month = 4; break;
						case 'May': $month = 5; break;
						case 'Jun': $month = 6; break;
						case 'Jul': $month = 7; break;
						case 'Aug': $month = 8; break;
						case 'Sep': $month = 9; break;
						case 'Oct': $month = 10; break;
						case 'Nov': $month = 11; break;
						case 'Dec': $month = 12; break;
			        }
			        $year = (int)$explodeDate[3];
			        
			        $explodeTime = explode(':', $explodeDate[4]);
			        $hours = (int)$explodeTime[0];
			        $minuts = (int)$explodeTime[1];
			        $seconds = (int)$explodeTime[2];
			        $publicationDate = mktime($hours, $minuts, $seconds, $month, $day, $year);
			        $rssData[] = ['title' => $title, 'description' => $description, 'link' => $link, 'publicationDate' => $publicationDate];
		    	}
		    	unset($url, $domDoc, $loadFlag, $items, $newsTitleObject, $titleText, $title, $newsDescriptionObject, $description);
		    	unset($newsLinkObject, $link, $newsDateObject, $codeOneItem, $publicationDate, $explodeDate, $day, $month, $year);
		    	unset($explodeTime, $hours, $minuts, $seconds, $publicationDate);

		    	return $rssData;
			} else {
				Maker::$app -> error('Произошла ошибка при чтении RSS файла');
			}
		} else {
			if($dataType !== 'string') {
				Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
			}
			if($url === '') {
				Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}
}