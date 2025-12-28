<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Quick start. Local server-side application with UI</title>
</head>
<body>
	<div id="auth-data">OAuth 2.0 data from REQUEST:
		<pre><?php
			print_r($_REQUEST);
			?>
		</pre>
	</div>
	<p>Это тестовое приложение</p>
	<div id="name">
		<?php
		require_once (__DIR__.'/crest.php');

		define('LOG_FILE', __DIR__ . '/event_log.txt');

		// Функция для записи в лог
		function writeToLog($message) {
			$timestamp = date('Y-m-d H:i:s');
			$logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
			file_put_contents(LOG_FILE, $logMessage, FILE_APPEND);
		}

		
		$handlerUrl = 'https://cm87341.tw1.ru/local/local-server-ui-index/handler.php';
		
		$result = CRest::call('event.unbind', [
			'event' => 'OnCrmActivityAdd',
			'handler' => $handlerUrl
		]);
		try {
			// 1. Проверяем, есть ли уже подписка (опционально, но рекомендуется)
			$existingEvents = CRest::call('event.get');
			$isAlreadyBound = false;
			writeToLog('Уже забинжены');
			writeToLog(print_r($existingEvents, true));

			if (is_array($existingEvents['result'])) {
				foreach ($existingEvents['result'] as $event) {
					if ($event['event'] === 'OnCrmActivityAdd' && $event['handler'] === $handlerUrl) {
						$isAlreadyBound = true;
						// echo "Подписка на событие OnCrmActivityAdd уже активна.\n";
						writeToLog('Подписка на событие OnCrmActivityAdd уже активна');
						break;
					}
				}
			}

			// 2. Если подписки нет - создаем ее
			if (!$isAlreadyBound) {
				$result = CRest::call('event.bind', [
					'event' => 'OnCrmActivityAdd',
					'handler' => $handlerUrl
				]);

				// 3. Проверяем результат
				if (isset($result['result']) && $result['result'] === true) {
					// echo "Успешно подписались на событие OnCrmActivityAdd!\n";
					writeToLog('Успешно подписались на событие OnCrmActivityAdd!');
					// Рекомендуется сохранить факт подписки в свою БД
				} else {
					// echo "Ошибка подписки: ";
					writeToLog('Ошибка подписки:');
					writeToLog( print_r($result, true));
					
				}
			}

		} catch (Exception $e) {
			writeToLog( $e->getMessage());
			// echo "Произошла ошибка: " . $e->getMessage() . "\n";
		}		
		
		// $result = CRest::call('user.current');
		// $result = CRestCurrent::call('user.current');
		// print_r($result);
		// echo $result['result']['NAME'].' '.$result['result']['LAST_NAME'];
		// echo '<PRE>';
		// print_r(CRest::call(
		// 	'crm.lead.add',
		// 	[
		// 		'fields' =>[
		// 			'TITLE' => 'Название лида',//Заголовок*[string]
		// 			'NAME' => 'Имя',//Имя[string]
		// 			'LAST_NAME' => 'Фамилия',//Фамилия[string]
		// 		]
		// 	])
		// );
		// echo '</PRE>';

		?>
	</div>
</body>
</html>