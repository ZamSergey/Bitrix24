<?php
namespace  Otus\Orm;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

/**
 * Class ElementPropS20Table
 * 
 * Fields:
 * <ul>
 * <li> IBLOCK_ELEMENT_ID int mandatory
 * <li> PROPERTY_75 text optional
 * <li> PROPERTY_76 text optional
 * </ul>
 *
 * @package Bitrix\Iblock
 **/

class ColorTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_iblock_element_prop_s20';
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
			))->configureTitle(Loc::getMessage('ELEMENT_PROP_S20_ENTITY_IBLOCK_ELEMENT_ID_FIELD'))
					->configurePrimary(true)
			,
			(new TextField('COL_NAME',
				[]
			))->configureTitle(Loc::getMessage('ELEMENT_PROP_S20_ENTITY_PROPERTY_75_FIELD'))
            ->configureColumnName('PROPERTY_75')
			,
			(new TextField('COL_CODE',
				[]
			))->configureTitle(Loc::getMessage('ELEMENT_PROP_S20_ENTITY_PROPERTY_76_FIELD'))
            ->configureColumnName('PROPERTY_76')
			,
             (new ManyToMany('APARTMENTS', BookTable::class))
                ->configureTableName('my_apartment_color')
                ->configureLocalPrimary('IBLOCK_ELEMENT_ID', 'COLOR_ID')
                ->configureRemotePrimary('ID', 'APARTMENT_ID'),
            // (new Reference(
            //     'ELEMENT',
            //     \Bitrix\Iblock\ElementTable::class,
            //     Join::on('this.IBLOCK_ELEMENT_ID', 'ref.ID')
            // ))
            //     ->configureJoinType('inner'),
		];
	}
}