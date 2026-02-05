<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Otus\Orm\BookTable;

use Bitrix\Main\Type\Date;
use Bitrix\Main\Error;

if (!\Bitrix\Main\Loader::includeModule('otus.crmcustomtabgarage')) {
    die(json_encode(['error' => 'Модуль не подключен']));
}

use Otus\Crmcustomtabgarage\Orm\CarTable;

class TestGridAjaxController extends \Bitrix\Main\Engine\Controller
{
    public function configureActions(): array
    {
        return [
            'deleteElement' => [
                'prefilters' => [],
            ],
            'addCar' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
        ];
    }

    public function addCarAction(): array
    {
       
        try {
            $carBrand = $this->request->get('carBrand');
            $carModel = $this->request->get('carModel');
            $carColor = $this->request->get('carColor');
            $carNumber = $this->request->get('carNumber');
            $carMile = $this->request->get('carMile');
            $carDate = $this->request->get('carDate');
            $clientId = $this->request->get('clientId');
            
            if (empty($carBrand)) {
                $this->errorCollection->add([ new Error('Не передан бренд')]);
                return [];
            }
            if (empty($carModel)) {
                $this->errorCollection->add([ new Error('Не передана модель')]);
                return [];
            }
            if (empty($carColor)) {
                $this->errorCollection->add([ new Error('Не передан цвет')]);
                return [];
            }
            if (empty($carNumber)) {
                $this->errorCollection->add([ new Error('Не передана номер')]);
                return [];
            }
            if (empty($clientId)) {
                $this->errorCollection->add([ new Error('Не передан id клиента')]);
                return [];
            }

            $addResult = CarTable::add([
                'BRAND' => $carBrand,
                'MODEL' =>  $carModel,
                'CAR_NUMBER' => $carNumber,
                'CAR_YEAR' => (new Date)->add('-3Y'),
                // 'MILEAGE' => intval($carMile),
                'MILEAGE' => $carMile,
                'COLOR' =>  $carColor,
                'CLIENT_ID' =>  intval($clientId),
            ]);

            if ($addResult->isSuccess()) {
                $result['BOOK_ID'] = $addResult->getId();
            } else {
                $this->errorCollection->add($addResult->getErrorMessages());
                return [];
            }
        } catch (\Exception $e) {
            $this->errorCollection->add([new Error($e->getMessage())]);
            return [];
        }

        return $result;
    }

    public function deleteElementAction(int $bookId): array
    {
        $result = [];

        try {
            $deleteResult = CarTable::delete($bookId);

            if ($deleteResult->isSuccess()) {
                return $result;
            } else {
                $this->errorCollection->add($deleteResult->getErrorMessages());
                return [];
            }

        } catch (\Exception $e) {
            $this->errorCollection->add([new Error($e->getMessage())]);
            return [];
        }
    }
}