<?php
require_once (__DIR__.'/crest.php');
include_once('parseResult.php');
define('LOG_FILE', __DIR__ . '/event_log.txt');

// Функция для записи в лог
function writeToLog($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
    file_put_contents(LOG_FILE, $logMessage, FILE_APPEND);
}

function findDealIdByActivityId($activityId) {
    // Получаем данные дела
    $result = CRest::call('crm.activity.get', [
        'id' => $activityId
    ]);
    
    if (!$result || !isset($result['result'])) {
        return false;
    }
    
    $activity = $result['result'];
    
    // Проверяем, связано ли дело со сделкой
    // OWNER_TYPE_ID = 2 означает "Сделка"
    if ($activity['OWNER_TYPE_ID'] == 2 && !empty($activity['OWNER_ID'])) {
        return intval($activity['OWNER_ID']);
    }
    
    return false;
}

writeToLog("Hello world!");
writeToLog(file_get_contents('php://input'));
http_response_code(200);
echo "Hello world!";

// Логируем входящий запрос для отладки
// writeToLog('b24_events.log', "\n" . date('Y-m-d H:i:s') . " - New Event:\n" . file_get_contents('php://input') . "\n", FILE_APPEND);

// Получаем данные от Bitrix24
$data = json_decode(file_get_contents('php://input'), true);
writeToLog('Jsone');


$akt = parseBitrixEventString(file_get_contents('php://input'));
$activityID = $akt['FIELDS']['ID']['data'];
 writeToLog(print_r($akt['FIELDS']['ID']['data'] ,true));

if($activityID) {
    $result = CRest::call('crm.activity.get', [
        'id' => intval($activityID)
    ]);

    $dealID = findDealIdByActivityId($activityID);
}



$dealID1 = findDealIdByActivityId($akt['FIELDS']['ID']['data']);
writeToLog("СделкаID: ".$dealID1 );

if( $dealID) {
     writeToLog('Обновляем сделку');           
  
    $result1 = CRest::call('crm.deal.update', [
        'id' => intval($dealID),
        'fields' => [
            'UF_LAST_COMUNICATE_DATE' => date('Y-m-d H:i:s')
        ]
    ]);
    writeToLog(print_r($result1 ,true)); 
}

$dealResult = CRest::call('crm.deal.get', [
        'id' => $dealID
    ]);

writeToLog("Сделка");
writeToLog(print_r($dealResult ,true));




         

// Проверяем, что это валидные данные события
if (isset($data['event']) && $data['event'] == 'OnCrmActivityAdd' && isset($data['data']['FIELDS']['ID'])) {
    
    $activityId = $data['data']['FIELDS']['ID'];
    writeToLog("Зашли в условие");
    writeToLog($activityId);
    
    // Ваша логика обработки нового дела
    // Например, получим полные данные по делу через REST API
    // ... 
    
    // Обязательно возвращаем статус '200 OK'
    http_response_code(200);
    echo json_encode(['status' => 'success']);
    exit;
}

// Если данные невалидны, все равно возвращаем 200, чтобы Bitrix24 не считал доставку неудачной
http_response_code(200);
echo json_encode(['status' => 'ignored']);



?>