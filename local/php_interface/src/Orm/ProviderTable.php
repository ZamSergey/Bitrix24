<?php
namespace Otus\Orm;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

/**
 * Class ElementPropS19Table
 * 
 * Fields:
 * <ul>
 * <li> IBLOCK_ELEMENT_ID int mandatory
 * <li> PROPERTY_73 text optional
 * <li> PROPERTY_74 text optional
 * </ul>
 *
 * @package Bitrix\Iblock
 **/

class ProviderTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_iblock_element_prop_s19';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			(new IntegerField('IBLOCK_ELEMENT_ID',
				[]
			))->configureTitle(Loc::getMessage('ELEMENT_PROP_S19_ENTITY_IBLOCK_ELEMENT_ID_FIELD'))
					->configurePrimary(true)
			,
			(new TextField('TOWN',
				[]
			))->configureTitle(Loc::getMessage('ELEMENT_PROP_S19_ENTITY_PROPERTY_73_FIELD'))
            ->configureColumnName('PROPERTY_73')
			,
			(new TextField('REC',
				[]
			))->configureTitle(Loc::getMessage('ELEMENT_PROP_S19_ENTITY_PROPERTY_74_FIELD'))
            ->configureColumnName('PROPERTY_74')
			,
            (new Reference(
                'ELEMENT',
                \Bitrix\Iblock\ElementTable::class,
                Join::on('this.IBLOCK_ELEMENT_ID', 'ref.ID')
            ))
                ->configureJoinType('inner'),
		];
	}
}