<?php
define('LOG_FILE', __DIR__ . '/webhook.txt');
//vwoi0zm3y8pzpaplbv031mjsc7w9ych3
// Функция для записи в лог
function writeToLog($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
    file_put_contents(LOG_FILE, $logMessage, FILE_APPEND);
}

writeToLog(print_r($_REQUEST, true));