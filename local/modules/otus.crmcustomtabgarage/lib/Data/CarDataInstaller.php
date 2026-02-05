<?php
namespace Otus\Crmcustomtabgarage\Data;

use Otus\Crmcustomtabgarage\Orm\CarTable;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Type\Date;

class CarDataInstaller
{
    /**
     * @throws SystemException
     * @throws \Exception
     */
    public static function addTestData(): void
    
    {
        $testData = [           
            [
                'BRAND' => 'Lada',
                'MODEL' => 'Vesta',
                'CAR_NUMBER' => 'CK718M37',
                'CAR_YEAR' => (new Date)->add('-3Y'),
                'MILEAGE' => 500,
                'COLOR' => 'Чёрный', 
                'CLIENT_ID' => 1,              
            ],
            [
                'BRAND' => 'Lada',
                'MODEL' => 'Granta',
                'CAR_NUMBER' => 'MO413M197',
                'CAR_YEAR' => (new Date)->add('-8Y'),
                'MILEAGE' => 1500,
                'COLOR' => 'Белый',
                'CLIENT_ID' => 1,               
            ],
            [
                'BRAND' => 'Lada',
                'MODEL' => 'Priora',
                'CAR_NUMBER' => 'EO919C87',
                'CAR_YEAR' => (new Date)->add('-4Y'),
                'MILEAGE' => 1500,
                'COLOR' => 'Синий', 
                'CLIENT_ID' => 2,              
            ]
        ];

        foreach ($testData as $itemData) {
            $itemData['PUBLISH_DATE'] = DateTime::createFromText($itemData['PUBLISH_DATE']);         

            $resultAdd = CarTable::add($itemData);
            if (!$resultAdd->isSuccess()) {
                throw new SystemException('Не удалось добавить тестовые данные: ' . implode(', ', $resultAdd->getErrorMessages()));
            }
            
        }
    }
}
