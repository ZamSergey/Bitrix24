<?php
namespace Otus\Orm;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

class GarageTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'garage';
    }

    public static function getMap()
    {
        return [
            (new IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),
            (new StringField('TITLE'))
                ->configureRequired()
                ->configureSize(255),
            (new IntegerField('CLIENT_ID')),

            (new Reference(
                'CLIENT',
                \Bitrix\Crm\ContactTable::class,
                Join::on('this.CLIENT_ID', 'ref.ID')
            ))               
                ->configureJoinType('left'),
           

            (new OneToMany('CARS', CarTable::class, 'GARAGE'))
                ->configureJoinType('inner'),
                
        ];
    }
}