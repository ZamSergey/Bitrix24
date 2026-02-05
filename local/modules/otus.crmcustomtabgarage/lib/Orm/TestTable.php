<?php
namespace Otus\Crmcustomtabgarage\Orm;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class TestTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'test_table';
    }

    public static function getMap(): array
    {
        return [
            (new IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete()
                ->configureTitle(Loc::getMessage('OTUS_TEST_TABLE_ID')),

            (new StringField('TITLE'))
                ->configureRequired()
                ->configureSize(255)
                ->configureTitle(Loc::getMessage('OTUS_TEST_TABLE_TITLE')),  

            (new TextField('DESCRIPTION'))
                ->configureTitle(Loc::getMessage('OTUS_TEST_TABLE_DESCRIPTION')),

            (new DateField('PUBLISH_DATE'))
                ->configureTitle(Loc::getMessage('OTUS_TEST_TABLE_PUBLISH_DATE')),
          
        ];
    }
}
