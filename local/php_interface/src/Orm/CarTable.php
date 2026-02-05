<?php
namespace Otus\Orm;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

class CarTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'car';
    }

    public static function getMap(): array
    {
        return [
            (new IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),

            (new StringField('BRAND'))
                ->configureRequired()
                ->configureSize(20),

            (new StringField('MODEL'))
                ->configureRequired()
                ->configureSize(20),

            (new StringField('CAR_NUMBER'))
                ->configureRequired()
                ->configureSize(20),

            (new DateField('CAR_YEAR')),            

            (new IntegerField('MILEAGE')),

            (new StringField('COLOR')),

            (new IntegerField('GARAGE_ID')),

            (new Reference(
                'GARAGE',
                GarageTable::class,
                Join::on('this.GARAGE_ID', 'ref.ID')
            ))
                ->configureJoinType('inner'),
        ];
    }
}