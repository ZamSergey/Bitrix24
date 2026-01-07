<?php
namespace Otus\Rest;

use Bitrix\Main\Application;
use Bitrix\Rest\RestException;
use Bitrix\Main\Event;

use Bitrix\Main\Localization\Loc;
use Otus\Orm\ApartmentcomplexTable;
use Bitrix\Main\Type\DateTime;

Loc::loadMessages(__FILE__);

class Events
{
    /**
     * Register rest methods
     * Clear scope cache after register
     * \Bitrix\Main\Data\Cache::clearCache(true, '/rest/scope/');
     * @return array[]
     */
    public static function OnRestServiceBuildDescriptionHandler(): array
    {
        return [
            
            'otus.rest' => [
                'otus.rest.add' => [__CLASS__, 'add'],
                'otus.rest.test' => [__CLASS__, 'test'],
                'otus.rest.update' => [__CLASS__, 'update'],
                'otus.rest.read' => [__CLASS__, 'read'],
                'otus.rest.delete' => [__CLASS__, 'delete'],
                
            ],
        ];
    }

    /**
     * Add element
     * @param $arParams - request params
     * @param $navStart - default start parameter (start from POST-data)
     * @param \CRestServer $server - server data
     * @return mixed
     * @throws RestException
     */
    public static function add($arParams, $navStart, \CRestServer $server)
    {
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'PARAMS: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'NAV: '.var_export($navStart, true).PHP_EOL, FILE_APPEND);
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'SERVER: '.var_export($server, true).PHP_EOL, FILE_APPEND);

       if(isset( $arParams['APARTMENT']['BUILD_DATE'])) {
            //Установка формата даты, иначе не сохраняется
            $arParams['APARTMENT']['BUILD_DATE'] = DateTime::createFromPhp(new \DateTime($arParams['APARTMENT']['BUILD_DATE']));
        }

        $originDataStoreResult = ApartmentcomplexTable::add($arParams['APARTMENT']);
        if ($originDataStoreResult->isSuccess())
        {
            $id = $originDataStoreResult->getId();
            $arParams['ID'] = $id;
            // $event = new Event('main', 'onAfterOtusBookAdd', $arParams);
            // $event->send();

            return $id;
        } else {
            throw new RestException(
                json_encode($originDataStoreResult->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
        // return "method add";
    }

        /**
     * Add element
     * @param $arParams - request params
     * @param $navStart - default start parameter (start from POST-data)
     * @param \CRestServer $server - server data
     * @return mixed
     * @throws RestException
     */
    public static function read($arParams, $navStart, \CRestServer $server)
    {
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'PARAMS: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'NAV: '.var_export($navStart, true).PHP_EOL, FILE_APPEND);
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'SERVER: '.var_export($server, true).PHP_EOL, FILE_APPEND);
        
        // Проверка существования комплекса
        $exists = ApartmentcomplexTable::getCount([            
            'ID' => $arParams['ID']
        ]);
        
        if (!$exists) {
            throw new RestException(
                'Invalid ID. Enter correct ID.',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }

        $originDataStoreResult = ApartmentcomplexTable::getById($arParams['ID']);
        
        if ($record = $originDataStoreResult->fetch()) {
            
            return($record);
       
        } else {
            throw new RestException(
                json_encode($originDataStoreResult->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        } 
        
    }

    /**
     * Обновлят информацию о книге по её ИД
     * @param $arParams
     * @param $navStart
     * @param \CRestServer $server
     * @return void
     */
    public static function update($arParams, $navStart, \CRestServer $server)
    {

       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'PARAMS: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'NAV: '.var_export($navStart, true).PHP_EOL, FILE_APPEND);
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'SERVER: '.var_export($server, true).PHP_EOL, FILE_APPEND);

        $request = Application::getInstance()->getContext()->getRequest();
        $isPost = $request->isPost();
        if (!$isPost) {
            throw new RestException(
                'Invalid HTTP METHOD. Use POST',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }

        $apartment = (int)($arParams['ID'] ?? 0);
        
        if ($apartment <= 0) {            
            throw new RestException(
                'ID undefind.',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
        
        $fields = $params['fields'] ?? $params;
        
        // Проверка существования комплекса
        $exists = ApartmentcomplexTable::getCount([            
            'ID' => $apartment
        ]);
        
        if (!$exists) {
            throw new RestException(
                'Invalid ID. Enter correct ID.',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
        
        
        $fieldsToUpdate = $request->getPost('fields');
        if(isset( $fieldsToUpdate['BUILD_DATE'])) {
            //Установка формата даты, иначе не сохраняется
            $fieldsToUpdate['BUILD_DATE'] = DateTime::createFromPhp(new \DateTime($fieldsToUpdate['BUILD_DATE']));
        }
        $updateResult = ApartmentcomplexTable::update($apartment, $fieldsToUpdate);
        if ($updateResult->isSuccess()) {
            return $apartment;
        } else {
            throw new RestException(
                json_encode($updateResult->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
        // return "method update";
    }

    /**
     * Обновлят информацию о книге по её ИД
     * @param $arParams
     * @param $navStart
     * @param \CRestServer $server
     * @return void
     */
    public static function delete($arParams, $navStart, \CRestServer $server)
    {
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'PARAMS: '.var_export($arParams, true).PHP_EOL, FILE_APPEND);
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'NAV: '.var_export($navStart, true).PHP_EOL, FILE_APPEND);
       file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRest.txt', 'SERVER: '.var_export($server, true).PHP_EOL, FILE_APPEND);

        $request = Application::getInstance()->getContext()->getRequest();
        $isPost = $request->isPost();
        if (!$isPost) {
            throw new RestException(
                'Invalid HTTP METHOD. Use POST',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }

        $apartment = (int)($arParams['ID'] ?? 0);
        
        if ($apartment <= 0) {            
            throw new RestException(
                'ID undefind.',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
        
        $fields = $params['fields'] ?? $params;
        
        // Проверка существования комплекса
        $exists = ApartmentcomplexTable::getCount([            
            'ID' => $apartment
        ]);
        
        if (!$exists) {
            throw new RestException(
                'Invalid ID. This ID does not exist.',
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
                
      
        $updateResult = ApartmentcomplexTable::delete($apartment);
        if ($updateResult->isSuccess()) {
            return $apartment;
        } else {
            throw new RestException(
                json_encode($updateResult->getErrorMessages(), JSON_UNESCAPED_UNICODE),
                RestException::ERROR_ARGUMENT,
                \CRestServer::STATUS_OK
            );
        }
        // return "method update";
    }

    public static function test($arParams, $navStart, \CRestServer $server)
    {
        return 'test666';
    }

    /**
     * Prepare data
     * @param $arguments - data
     * @param $handler - handler
     * @return mixed
     */
    public static function prepareEventData($arguments, $handler)
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRestEvent.txt', 'A: '.var_export($arguments, true).PHP_EOL, FILE_APPEND);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRestEvent.txt', 'H: '.var_export($handler, true).PHP_EOL, FILE_APPEND);

        /** @var Event $event */
        $event = reset($arguments);
        $response = $event->getParameters();

        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logRestEvent.txt', 'R: '.var_export($response, true).PHP_EOL, FILE_APPEND);

        return $response;
    }
}