<?php
namespace Otus\Crmcustomtabgarage\Data;

use Otus\Crmcustomtabgarage\Orm\TestTable;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;

class TestDataInstaller
{
    /**
     * @throws SystemException
     * @throws \Exception
     */
    public static function addTestData(): void
    {
        $testData = [
            [
                'TITLE' => 'Первая тестовая запись',               
                'DESCRIPTION' => 'Тестовая информация для заполнения грида',
                'PUBLISH_DATE' => '01.01.2025',               
            ],
            [
                'TITLE' => 'Вторая тестовая запись',               
                'DESCRIPTION' => 'Тестовая информация для заполнения грида',
                'PUBLISH_DATE' => '12.11.2025',               
            ],
            [
                'TITLE' => 'Третья тестовая запись',               
                'DESCRIPTION' => 'Тестовая информация для заполнения грида',
                'PUBLISH_DATE' => '11.03.2025',               
            ],
            [
                'TITLE' => 'Четвертая тестовая запись',               
                'DESCRIPTION' => 'Тестовая информация для заполнения грида',
                'PUBLISH_DATE' => '07.04.2025',               
            ],
            [
                'TITLE' => 'Пятая тестовая запись',               
                'DESCRIPTION' => 'Тестовая информация для заполнения грида',
                'PUBLISH_DATE' => '21.08.2025',               
            ]
        ];

        foreach ($testData as $itemData) {
            $itemData['PUBLISH_DATE'] = DateTime::createFromText($itemData['PUBLISH_DATE']);         

            $resultAdd = TestTable::add($itemData);
            if (!$resultAdd->isSuccess()) {
                throw new SystemException('Не удалось добавить тестовые данные: ' . implode(', ', $resultAdd->getErrorMessages()));
            }
            
        }
    }
}
