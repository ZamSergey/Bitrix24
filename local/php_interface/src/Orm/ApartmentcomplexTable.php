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

class ApartmentcomplexTable extends DataManager 
{
    public static function getTableName() : string 
    {
        return 'apartment_complex';
    }

    public static function getMap() : array
    {
          return [
            (new IntegerField('ID'))
                ->configurePrimary()
                ->configureAutocomplete(),

            (new StringField('TITLE'))
                ->configureRequired()
                ->configureSize(255),

            (new IntegerField('FLATS')),            

            (new TextField('DESCRIPTION')),

            (new DateField('BUILD_DATE')),

            (new ManyToMany('COLORS', ColorTable::class))
                ->configureTableName('my_apartment_color')
                ->configureLocalPrimary('ID', 'APARTMENT_ID')
                ->configureRemotePrimary('IBLOCK_ELEMENT_ID', 'COLOR_ID'),
           

            (new IntegerField('DEVELOPER_ID')),

            (new Reference(
                'DEVELOPER',
                // \Bitrix\Iblock\ElementTable::class,
                ProviderTable::class,
                Join::on('this.DEVELOPER_ID', 'ref.IBLOCK_ELEMENT_ID')
            ))
                ->configureJoinType('inner'),
        ];
    }
}