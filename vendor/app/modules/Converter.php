<?php

namespace liw\vendor\app\modules;

class Converter
{
	public function synonymyLink() {
		//Получаем ссылки-синонимы, указанные пользователем
		$linkManager = \liw\vendor\app\Maker::$app -> configData['linkMamager'];
		//Если ссылки-синонисы были указаны пользователем
		if($linkManager !== []) {
			//Получаем список ключей массива, являющиеся исходными сслками
			$userUrls = array_keys($linkManager);
			//Пробегаем все получсенные ссылки
			foreach ($userUrls as $userUrl) {
				$userUrl = strtolower($userUrl);
				//Если текущая ссылка из массив совпадает с запрошенной
				if($userUrl === $_SERVER['REQUEST_URI']) {
					//берем ссылку - синоним
					$url = strtolower($linkManager[$userUrl]);
					break;
				} else {
					$url = strtolower($_SERVER['REQUEST_URI']);
				}
			}
		} else { //Если пользователь не указал ссылки-синонимы
			$url = $_SERVER['REQUEST_URI'];
		}
		unset($linkManager, $userUrls, $userUrl);

		return $url;
	}

	//Метод формирования имени контроллера
	public function getControllerName($url) {

		$dataType = gettype($url);
		if(($dataType === 'string') && ($url !== '')) {
			//Если в ссылке первый символ являетяс слешем
			if(strpos($url, '/') === 0) {
				//удаляем его
				$url = mb_substr($url, 1);
			}
			$controllerName = '';
			//Разделяем ссылку
			$explodeUrl = explode('/', $url);
			//Считаем сколько частей в ссылке
			$numberUrlPart = count($explodeUrl);
			//Если последняя часть после слеша не пустая
			if($explodeUrl[$numberUrlPart - 1] !== '') {
			    //проверяем правильность формата имени контроллера
				\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 2]);
			    $controllerNameInUrl = $explodeUrl[$numberUrlPart - 2];
			   
			} else { //Если пустая
				//Если часть ссылки перед послдним слешем не пустая
				if($explodeUrl[$numberUrlPart - 2] !== '') {
					\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 3]);
			   		$controllerNameInUrl = $explodeUrl[$numberUrlPart - 3];
				} else { //Если пустая
					$indexUrl = strtolower(\liw\vendor\app\Maker::$app -> configData['indexUrl']);
					$explodeUrl = explode('/', $indexUrl);
					$numberUrlPart = count($explodeUrl);
					\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 2]);
			   		$controllerNameInUrl = $explodeUrl[$numberUrlPart - 2];
				}
			}

			//Если в имене контроллера нет "-"
			if(strpos($controllerNameInUrl, '-') === false) {
				//Делаем заглавной первую букву
				$controllerName = substr_replace($controllerNameInUrl, strtoupper(mb_substr($controllerNameInUrl, 0, 1)), 0, 1);
			} else { //если "-" есть
				//Разделяем часть ссылки
				$explodeControllerNameInUrl = explode('-', $controllerNameInUrl);
				$controllerName = '';
				//Проходим все части имени контроллера
				foreach ($explodeControllerNameInUrl as $partControllerNameInUrl) {
					//Делаем заглавной первую букву
					$controllerName .= substr_replace($partControllerNameInUrl, strtoupper(mb_substr($partControllerNameInUrl, 0, 1)), 0, 1);
				}
			}

			unset($url, $explodeUrl, $numberUrlPart, $controllerNameInUrl);

			return $controllerName .= 'Controller';
		} else {
			if($dataType !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
			}
			if($url === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}

	//Метод формирования имени действия в контроллере
	public function getActionName($url) {
		$dataType = gettype($url);
		if(($dataType === 'string') && ($url !== '')) {
			//Если в ссылке первый символ являетяс слешем
			if(strpos($url, '/') === 0) {
				//удаляем его
				$url = mb_substr($url, 1);
			}
			//Разделяем ссылку
			$explodeUrl = explode('/', $url);
			//Считаем сколько частей в ссылке
			$numberUrlPart = count($explodeUrl);
			//Если последняя часть после слеша не пустая
			if($explodeUrl[$numberUrlPart - 1] !== '') {
				\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 1]);
			    $actionNameInUrl = $explodeUrl[$numberUrlPart - 1];
			} else { //Если пустая
				//Если часть ссылки перед послдним слешем не пустая
				if($explodeUrl[$numberUrlPart - 2] !== '') {
					\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 2]);
			    	$actionNameInUrl = $explodeUrl[$numberUrlPart - 2];
				} else { //Если пустая
					$indexUrl = strtolower(\liw\vendor\app\Maker::$app -> configData['indexUrl']);
					$explodeUrl = explode('/', $indexUrl);
					$numberUrlPart = count($explodeUrl);
					\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 1]);
			    	$actionNameInUrl = $explodeUrl[$numberUrlPart - 1];
				}
			}

			if(strpos($actionNameInUrl, '-') === false) {
				$actionName = substr_replace($actionNameInUrl, strtoupper(mb_substr($actionNameInUrl, 0, 1)), 0, 1);
			} else {
				$explodeActionNameInUrl = explode('-', $actionNameInUrl);
				$actionName = '';
				foreach ($explodeActionNameInUrl as $partActionNameInUrl) {
					$actionName .= substr_replace($partActionNameInUrl, strtoupper(mb_substr($partActionNameInUrl, 0, 1)), 0, 1);
				}
			}

			unset($url, $explodeUrl, $numberUrlPart, $actionNameInUrl);

			return $actionName = 'action'.$actionName;
		} else {
			if($dataType !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
			}
			if($url === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}

	//Метод формирования имени виджета
	public function getWidgetName($url) {

		$dataType = gettype($url);
		if(($dataType === 'string') && ($url !== '')) {
			//Если в ссылке первый символ являетяс слешем
			if(strpos($url, '/') === 0) {
				//удаляем его
				$url = mb_substr($url, 1);
			}
			$widgetName = '';
			//Разделяем ссылку
			$explodeUrl = explode('/', $url);
			//Считаем сколько частей в ссылке
			$numberUrlPart = count($explodeUrl);
			//Если последняя часть после слеша не пустая
			if($explodeUrl[$numberUrlPart - 1] !== '') {
			    //проверяем правильность формата имени контроллера
				\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 2]);
			    $widgetNameInUrl = $explodeUrl[$numberUrlPart - 2];
			   
			} else { //Если пустая
				//Если часть ссылки перед послдним слешем не пустая
				if($explodeUrl[$numberUrlPart - 2] !== '') {
					\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 3]);
			   		$widgetNameInUrl = $explodeUrl[$numberUrlPart - 3];
				} else { //Если пустая
					$indexUrl = strtolower(\liw\vendor\app\Maker::$app -> configData['indexUrl']);
					$explodeUrl = explode('/', $indexUrl);
					$numberUrlPart = count($explodeUrl);
					\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 2]);
			   		$widgetNameInUrl = $explodeUrl[$numberUrlPart - 2];
				}
			}

			//Если в имене контроллера нет "-"
			if(strpos($widgetNameInUrl, '-') === false) {
				//Делаем заглавной первую букву
				$widgetName = $widgetNameInUrl;
			} else { //если "-" есть
				//Разделяем часть ссылки
				$explodewidgetNameInUrl = explode('-', $widgetNameInUrl);
				$widgetName = $explodewidgetNameInUrl[0];
				$numberExplodewidgetNameInUrl = count($explodewidgetNameInUrl);
				//Проходим все части имени контроллера
				for($i = 1; $i < $numberExplodewidgetNameInUrl; $i++) {
					//Делаем заглавной первую букву
					$widgetName .= substr_replace($explodewidgetNameInUrl[$i], strtoupper(mb_substr($explodewidgetNameInUrl[$i], 0, 1)), 0, 1);
				}
			}

			unset($url, $explodeUrl, $numberUrlPart, $widgetNameInUrl);

			return $widgetName;
		} else {
			if($dataType !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
			}
			if($url === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}

	//Метод формирования ссылки для вида
	public function getViewPuth($url, $viewName) {
		$dataType1 = gettype($url);
		$dataType2 = gettype($viewName);
		if(($dataType1 === 'string') && ($dataType2 === 'string')
			&& ($url !== '') && ($viewName !== '')) {
			//Если в ссылке первый символ являетяс слешем
			if(strpos($url, '/') === 0) {
				//удаляем его
				$url = mb_substr($url, 1);
			}
			//Разделяем ссылку
			$explodeUrl = explode('/', $url);
			//Считаем сколько частей в ссылке
			$numberUrlPart = count($explodeUrl);
			//Если последняя часть после слеша не пустая
			if($explodeUrl[$numberUrlPart - 1] !== '') {
				\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 2]);
			    $controllerNameInUrl = $explodeUrl[$numberUrlPart - 2];
			} else { //Если пустая
				//Если часть ссылки перед послдним слешем не пустая
				if($explodeUrl[$numberUrlPart - 2] !== '') {
					\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 3]);
					$controllerNameInUrl = $explodeUrl[$numberUrlPart - 3];
				} else { //Если пустая
					$explodeUrl = explode('/', \liw\vendor\app\Maker::$app -> configData['indexUrl']);
					$numberUrlPart = count($explodeUrl);
					\liw\vendor\app\Maker::$app -> checkControllerActionName($explodeUrl[$numberUrlPart - 2]);
					$controllerNameInUrl = $explodeUrl[$numberUrlPart - 2];
				}
			}

			$viewPuth = __DIR__.'/../../../views/'.strtolower($controllerNameInUrl).'/'.strtolower($viewName).'.php';
			if(file_exists($viewPuth)) {
				return $viewPuth;
			} else {
				\liw\vendor\app\Maker::$app -> error('Вид с указанным названием не найден. Проверенный путь: '.$viewPuth);
			}
		} else {
			if($dataType1 !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных первого входного параметра не соответствует типу string');
			}
			if($dataType2 !== string) {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных второго входного параметра не соответствует типу string');
			}
			if($url === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные в первом входном параметре');
			}
			if($viewName === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во втором входном параметре');
			}
		}

	}

	//Метод разделения ссылки на домен и путь
	public function devideUrl($url) {
		$type = gettype($url);
		if(($type === 'string') && ($url !== '')) {
			//Если пользователь вставил в поле неполную ссылку
			if(strpos($url, 'http') === false) {
				//Если ссылка не состоит из нескольких частей
				if(strpos($url, '/') === false) {
					return ['domain' => $url, 'path' => '/'];
				} else {
					$explodeUrl = explode('/', $url, 2);

					unset($url, $type);
					return ['domain' => $explodeUrl[0], 'path' => '/'.$explodeUrl[1]];
				}
			} else { //Если была вставлена ссылка с протоколом передачи данных
				//Парсим ссылку
				$parseUrl = parse_url($url);

				unset($url, $type);
				return ['domain' => $parseUrl['host'], 'path' => $parseUrl['path']];
			}
		} else {
			if($type !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
			}
			if($url === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}

	//Метод конвертирования пути с русскими буквами
	public function encodingPuth($url) {
		$type = gettype($url);
		if(($type == 'string') && ($url != '')){
			$encodingLetters = array(array('А', '%d0%90'), array('Б', '%d0%91'), array('В', '%d0%92'), array('Г', '%d0%93'),
			array('Д', '%d0%94'), array('Е', '%d0%95'), array('Ё', '%d0%81'), array('Ж', '%d0%96'), array('З', '%d0%97'),
			array('И', '%d0%98'), array('Й', '%d0%99'), array('К', '%d0%9a'), array('Л', '%d0%9b'), array('М', '%d0%9c'),
			array('Н', '%d0%9d'), array('О', '%d0%9e'), array('П', '%d0%9f'), array('Р', '%d0%a0'), array('С', '%d0%a1'),
			array('Т', '%d0%a2'), array('У', '%d0%a3'), array('Ф', '%d0%a4'), array('Х', '%d0%a5'), array('Ц', '%d0%a6'),
			array('Ч', '%d0%a7'), array('Ш', '%d0%a8'), array('Щ', '%d0%a9'), array('Ъ', '%d0%aa'), array('Ы', '%d0%ab'),
			array('Ь', '%d0%ac'), array('Э', '%d0%ad'), array('Ю', '%d0%ae'), array('Я', '%d0%af'), array('а', '%d0%b0'),
			array('б', '%d0%b1'), array('в', '%d0%b2'), array('г', '%d0%b3'), array('д', '%d0%b4'), array('е', '%d0%b5'),
			array('ё', '%d1%91'), array('ж', '%d0%b6'), array('з', '%d0%b7'), array('и', '%d0%b8'), array('й', '%d0%b9'),
			array('к', '%d0%ba'), array('л', '%d0%bb'), array('м', '%d0%bc'), array('н', '%d0%bd'), array('о', '%d0%be'),
			array('п', '%d0%bf'), array('р', '%d1%80'), array('с', '%d1%81'), array('т', '%d1%82'), array('у', '%d1%83'),
			array('ф', '%d1%84'), array('х', '%d1%85'), array('ц', '%d1%86'), array('ч', '%d1%87'), array('ш', '%d1%88'),
			array('щ', '%d1%89'), array('ъ', '%d1%8a'), array('ы', '%d1%8b'), array('ь', '%d1%8c'), array('э', '%d1%8d'),
			array('ю', '%d1%8e'), array('я', '%d1%8f'));

			foreach($encodingLetters as $oneLineEncodingLetters) {
				$url = preg_replace("/$oneLineEncodingLetters[0]/", "$oneLineEncodingLetters[1]", $url);
			}

			unset($type, $encodingLetters, $oneLineEncodingLetters);
			return $url;
		} else {
		  	if($type !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');
			}
			if($url === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}

	//Метод проверки существования ссылки, с учетом протокола передачи данных и www
	public function getFullLink($url) {
		$type = gettype($url);
    	if(($type === 'string') && ($url !== '')) {
    		//Формируем массив с вариатами сслки
        	$urlVariants = array('http://'.$url, 'http://'.$url.'/', 'https://'.$url, 'https://'.$url.'/', 'http://www.'.$url, 'http://www.'.$url.'/', 'https://www.'.$url, 'https://www.'.$url.'/', $url);
        	//пробегаем каждый вариант ссылки
      		for ($i = 0; $i < 9; $i++) {
      			//Делаем запрос хэдера страницы
		        $curlQuery = curl_init();
		        curl_setopt($curlQuery, CURLOPT_HEADER, 1);
		        curl_setopt($curlQuery, CURLOPT_NOBODY, 1);
		        curl_setopt($curlQuery, CURLOPT_TIMEOUT, 5);
		        curl_setopt($curlQuery, CURLOPT_RETURNTRANSFER, 1);
		        curl_setopt($curlQuery, CURLOPT_SSL_VERIFYPEER, false);
		        curl_setopt($curlQuery, CURLOPT_URL, $urlVariants[$i]);
		        $headerData = curl_exec($curlQuery);
		        curl_close($curlQuery);

		        //Если при запросе была ошибка
		        if($headerData === false) {
		        	//Если ошибка была менее восьми раз
					if($i !== 8) {
						//идем проверять следующий вариант ссылки
						continue;
					} else {
						\liw\vendor\app\Maker::$app -> error('Ссылка '.$url.' не доступна или не существует', 1);
					}
		        }
		 		
		 		//Разделяем ответ сервера
		        $explodeHeaderData = explode(' ', $headerData);
		        //Если в есть код овтета 200
		        if($explodeHeaderData[1] === '200') {
		        	unset($url, $type, $curlQuery, $headerData, $explodeHeaderData);

					return $urlVariants[$i];
					break;
		        } else {
		        	//Если были проверены все варианты ссылки
					if($i === 8) {
						\liw\vendor\app\Maker::$app -> error('Ссылка '.$url.' не доступна или не существует', 1);
					}
				}
		    }
		} else {
		    if($type !== 'string') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных входного параметра не соответствует типу string');;
			}
			if($url === '') {
				\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во входном параметре');
			}
		}
	}

	//Метод разделения текста по тегам
	public function devideByTag($data, $inputSepatator, $outputSepatator) {
		$type1 = gettype($data);
		$type2 = gettype($inputSepatator);
		$type3 = gettype($outputSepatator);
		if(($type1 === 'string') 
			&& ($type2 === 'string') 
			&& ($type3 === 'string') 
			&& ($data !== '') 
			&& ($inputSepatator !== '') 
			&& ($outputSepatator !== '')) {
			$devidedText = '';
			//Разделяем текст по тегам
			$explodeText = explode('&lt;'.$inputSepatator, $data);
			//Пробегаем каждую получившуюсячасти
		    foreach($explodeText as $onePartText) {
		    	//Удаляем пробелы
	    		$onePartText = trim($onePartText);
	    		//Если текущая часть не соответсвует одному из указанных
	    		if(($onePartText !== '/&gt;') && ($onePartText !== '')) {
	    			//Сохраняем текст и добавляем разделитель
	      			$devidedText .= $onePartText.$outputSepatator;
	    		}
	 		}
	 		unset($data, $inputSepatator, $outputSepatator, $type1, $type2, $type3, $explodeText, $onePartText);
	 		
	 		return $devidedText;
	 	} else {
	 		if($type1 !== 'string') {
	 			\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных первого входного параметра не соответствует типу string');
	 		}
	 		if($type2 !== 'string') {
	 			\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных второго входного параметра не соответствует типу string');
	 		}
	 		if($type3 !== 'string') {
	 			\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных третьего входного параметра не соответствует типу string');
	 		}
	 		if($data === '') {
	 			\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные в первом входном параметре');
	 		}
	 		if($inputSepatator === '') {
	 			\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во втором входном параметре');
	 		}
	 		if($outputSepatator === '') {
	 			\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные в третьем входном параметре');
	 		}
	 	}
	}

	//Метод удаления тегов
	public function deleteHtmlTags($data, $tags) {
		$type1 = gettype($data);
		$type2 = gettype($tags);
		if(($type1 === 'string') && ($type2 === 'array') && ($data !== '')) {
			//Если не указали теги
			if($tags === []) {
				//Удаляем все теги
				$data = strip_tags($data);
			} else { //Если теги указали
				//Пробегаем все теги
				foreach ($tags as $tag) {
					//Удаляем их
				    $data = preg_replace('/&lt;'.$tag.'.*&gt;|\/&gt;/isU', '', $data);
				}
			}
			unset($tags, $type1, $type2, $tag);

			return $data;
		} else {
			if($type1 !== 'string') {
	 			\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных первого входного параметра не соответствует типу string');
	 		}
	 		if($type2 !== 'array') {
	 			\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных второго входного параметра не соответствует типу array');
	 		}
			if($data === '') {
	 			\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные в первом входном параметре');
	 		}
		}
	}

	//Конвертирует внешние сылки в нужный нам вид, добавляя nofollow b target="_blanck"
	public function convertExternalUrls($data, $cssClass)
    {
    	$type1 = gettype($data);
    	$type2 = gettype($cssClass);
    	if(($type1 === 'string') && ($type2 === 'string') && ($data !== '')) {
		    $urlTags = '';
		    //Получаем ссылки
	        $numberFindedUrlTags = preg_match_all("/&lt;a.*\/a&gt;/isU", $data, $urlTags);
	        if(($numberFindedUrlTags !== 0) && ($numberFindedUrlTags !== false)) {
	        	//Пробегаем все найденные ссылки
	        	for($j = 0; $j < $numberFindedUrlTags; $j++) {
	            	$codeWithUrl = '';
		            $urlDescription = '';
		            //Получаем саму ссылку из кода со ссылкой
		            $findCodeUrlInUrlTags = preg_match("/href.*(&quot;|&#039;).*(&quot;|&#039;)/isU", $urlTags[0][$j], $codeWithUrl);
		            //Получаем описание из ссылки
		            $findDescriptionUrlInUrlTags = preg_match("/&gt;.*&lt;/isU", $urlTags[0][$j], $urlDescription);
		            //Если нашли ссылки в кодах ссылок
	            	if(($findCodeUrlInUrlTags !== 0) 
		                && ($findDescriptionUrlInUrlTags !== 0)
		                && ($findCodeUrlInUrlTags !== false) 
		                && ($findDescriptionUrlInUrlTags !== false)) {
	            		//Очищаем код со ссылкой
			            $clearUrl = preg_replace("/href|=|&quot;|&#039;/isU", '', $codeWithUrl[0]);
			        	//очищаем описание
			            $clearDescription = preg_replace("/\/|&lt;|&gt;/isU", '', $urlDescription[0]);
			            //Если было указано имя CSS класса, который нужно привязать ссылке
		            	if($cssClass !== '') {
		            		$cssClassCode = 'class=&quot;'.$cssClass.'&quot;';
		            	} else {
		            		$cssClassCode = '';
		            	}
		            	//Форимруем новый код ссылки
	          			$newUrl = '&lt;a href=&quot;'.$clearUrl.'&quot; '.$cssClassCode.' target=&quot;_blanck&quot; rel=&quot;nofollow&quot;&gt;'.$clearDescription.'&lt;/a&gt;';
	          			//Экранируем код со ссылкой, чтобы его можно использовать в регулярке далее
		                $quoteUrlCode = preg_quote($urlTags[0][$j], '/');
		                //Переделываем описание ссылки в нижний регистр
		                $lowerClearDescription = mb_strtolower($clearDescription, 'UTF-8');
		                //Есть нет указанного слова, знаичт скорее всего это сслыка не типа "читать далее"
		                if(strpos($lowerClearDescription, 'читать') === false) {
		                	//Заменяем старый код ссылки на новый
		                	$data = preg_replace("/$quoteUrlCode/isU", $newUrl, $data);
		            	} else {
		            		//Заменяем ссылку на пустое
		            		$data = preg_replace("/$quoteUrlCode/isU", '', $data);
		            	}
	            	}
	        	}
	        	unset($cssClass, $urlTags, $numberFindedUrlTags, $j, $codeWithUrl, $urlDescription, $findCodeUrlInUrlTags);
	        	unset($findDescriptionUrlInUrlTags, $clearUrl, $clearDescription, $cssClassCode, $newUrl, $quoteUrlCode);

	        	return $data;
	        } else { //Если ссылок не налось или произошла ошибка
	        	if($numberFindedUrlTags === false) {
	        		\liw\vendor\app\Maker::$app -> error('Произошла ошибка при поиске в обрабатывемом тексте кодов ссылок');
	        	} else { //Если не произошла ошибка
	        		unset($cssClass, $urlTags, $numberFindedUrlTags);

	        		return $data;
	        	}
	        }
	    } else {
	    	if($type1 !== 'string') {
	    		\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных первого входного параметра не соответствует типу string');
	    	}
	    	if($type2 !== 'string') {
	    		\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' тип данных второго входного параметра не соответствует типу string');
	    	}
	    	if($data === '') {
	    		\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные в первом входном параметре');
	    	}
	    	if($cssClass === '') {
	    		\liw\vendor\app\Maker::$app -> error('В методе '.__METHOD__.' не указаны данные во втором входном параметре');
	    	}
	    }
    }

}