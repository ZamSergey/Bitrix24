<?php
function parseBitrixEventString($queryString) {
    $result = [];
    
    // Разбиваем строку по амперсандам на пары ключ-значение
    $pairs = explode('&', $queryString);
    
    foreach ($pairs as $pair) {
        // Разбиваем каждую пару на ключ и значение
        list($key, $value) = explode('=', $pair, 2);
        
        // URL-декодируем ключ и значение
        $key = urldecode($key);
        $value = urldecode($value);
        
        // Обрабатываем вложенные ключи (например, data[FIELDS][ID])
        if (strpos($key, '[') !== false) {
            // Разбираем ключ на части: data[FIELDS][ID] -> ['data', 'FIELDS', 'ID']
            preg_match_all('/\[([^]]+)]|([^[]+)/', $key, $matches);
            $keys = array_filter(array_merge($matches[1], $matches[2]));
            
            // Создаем вложенную структуру
            $current = &$result;
            foreach ($keys as $k) {
                if (!isset($current[$k])) {
                    $current[$k] = [];
                }
                $current = &$current[$k];
            }
            $current = $value;
            unset($current);
        } else {
            // Простой ключ без вложенности
            $result[$key] = $value;
        }
    }
    
    return $result;
}