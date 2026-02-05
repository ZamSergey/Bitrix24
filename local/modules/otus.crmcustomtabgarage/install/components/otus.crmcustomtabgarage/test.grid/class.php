<?php

use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Otus\Crmcustomtabgarage\Orm\CarTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Query\Result;
use Bitrix\UI\Buttons\Color;
use Bitrix\Main\Error;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorableImplementation;

Loader::includeModule('otus.crmcustomtabgarage');
class TestGrid extends \CBitrixComponent implements Controllerable
{
     public function onPrepareComponentParams($arParams): array
    {
        $arParams['BOOK_PREFIX'] = strtolower($arParams['BOOK_PREFIX']);
        return $arParams;
    }

    public function listKeysSignedParameters(): array
    {
        return [
            'ORM_CLASS',
        ];
    }
    public function configureActions(): array
    {
         return [
            'deleteElement' => [
                'preFilters' => [
                    new \Bitrix\Main\Engine\ActionFilter\Authentication,
                ],
            ],
            'addElement' => [],
        ];
    }

        protected function getButtons(): array
    {
        return [
           
            [
                'click' => 'BX.Otus.TestGrid.addBook',
                'text' => 'Добавить автомобиль',
                'color' => Color::PRIMARY_DARK,
            ],
            [
                'click' => 'BX.Otus.TestGrid.createTestElementViaModule',
                'text' => 'Добавить тестовый автомобиль',
                'color' => Color::DANGER_DARK,
            ]
        ];
    }

    private function getElementActions(array $fields): array
    {
        return [
            [
                'onclick' => "window.open('http://192.168.1.185/bitrix/admin/perfmon_row_edit.php?lang=ru&table_name=car2&pk%5BID%5D={$fields['ID']}')", // метод обработчик в js
                'text' => Loc::getMessage('BOOK_GRID_OPEN_BOOK', [
                    '#BOOK_NAME#' => $fields['TITLE'],
                ]),
                'default' => true,
            ],
            [
                'onclick' => sprintf('BX.Otus.TestGrid.deleteCar(%d)', $fields['ID']),
                'text' => Loc::getMessage('BOOK_GRID_DELETE'),
                'default' => true,
            ],
            [
                'onclick' => sprintf('BX.Otus.TestGrid.deleteCarViaAjax(%d)', $fields['ID']),
                'text' => Loc::getMessage('BOOK_GRID_DELETE') . ' через AJAX',
                'default' => true,
            ],
            [
                'onclick' => sprintf('BX.Otus.TestGrid.showMessage("TEST")'),
                'text' => 'Вывести сообщение',
                'default' => true,
            ],
        ];
    }

    private function getHeaders(): array
    {
        return [
            [
                'id' => 'ID',
                'name' => 'ID',
                'sort' => 'ID',
                'default' => true,
            ],
            [
                'id' => 'BRAND',
                'name' => Loc::getMessage('CAR_GRID_CAR_BRAND_LABEL'),
                'sort' => 'BRAND',
                'default' => true,
            ],
            [
                'id' => 'MODEL',
                'name' => Loc::getMessage('CAR_GRID_CAR_MODEL_LABEL'),
                'sort' => 'MODEL',
                'default' => true,
            ],
            [
                'id' => 'CAR_NUMBER',
                'name' => Loc::getMessage('CAR_GRID_CAR_NUMBER_LABEL'),
                'sort' => 'CAR_NUMBER',
                'default' => true,
            ],
            [
                'id' => 'CAR_YEAR',
                'name' => Loc::getMessage('CAR_GRID_CAR_YEAR_LABEL'),
                'default' => true,
            ],
            [
                'id' => 'COLOR',
                'name' => Loc::getMessage('CAR_GRID_CAR_COLOR_LABEL'),
                'sort' => 'COLOR',
                'default' => true,
            ],
        ];
    }

    public function executeComponent(): void
    {
        $this->arResult['BUTTONS'] = $this->getButtons();
        $this->prepareGridData();
        $this->includeComponentTemplate();
    }

    public function getContactId(): int
    {   
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        $referer = $request->getServer()->get('HTTP_REFERER') ?: '';
        if (!empty($referer)) {
            $patterns = [
                '/\/crm\/contact\/details\/(\d+)/',
                '/\/crm\/company\/details\/(\d+)/',
                '/\/crm\/lead\/details\/(\d+)/',
                '/\/crm\/deal\/details\/(\d+)/',
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $referer, $matches)) {
                    return (int)$matches[1];
                }
            }
        }

        return 0;
    }


    private function prepareGridData(): void
    {
        $this->arResult['HEADERS'] = $this->getHeaders();
        $this->arResult['FILTER_ID'] = 'BOOK_GRID2';

        $gridOptions = new GridOptions($this->arResult['FILTER_ID']);
        $this->arResult['USED_HEADERS'] = $gridOptions->getUsedColumns($this->arResult['HEADERS']);
        $navParams = $gridOptions->getNavParams();

        $nav = new PageNavigation($this->arResult['FILTER_ID']);
        $nav->allowAllRecords(true)
            ->setPageSize($navParams['nPageSize'])
            ->initFromUri();

        $filterOption = new FilterOptions($this->arResult['FILTER_ID']);
        $filterData = $filterOption->getFilter([]);
        $filter = $this->prepareFilter($filterData);


        $sort = $gridOptions->getSorting([
            'sort' => [
                'ID' => 'DESC',
            ],
            'vars' => [
                'by' => 'by',
                'order' => 'order',
            ],
        ]);

        $bookIdsQuery = CarTable::query()
            ->setSelect(['ID'])
            ->setFilter($filter)
            ->setLimit($nav->getLimit())
            ->setOffset($nav->getOffset())
            ->setOrder($sort['sort'])
        ;

        $countQuery = CarTable::query()
            ->setSelect(['ID'])
            ->setFilter($filter)
        ;
        $nav->setRecordCount($countQuery->queryCountTotal());

        $bookIds = array_column($bookIdsQuery->exec()->fetchAll() ?? [], 'ID');       
    
        $contactId = $this->getContactId();
              
        if (!empty($bookIds)) {
            $books = CarTable::getList([
                'filter' => ['ID' => $bookIds, 'CLIENT_ID' => $contactId] + $filter,
                'select' => [
                    'ID',
                    'BRAND',
                    'MODEL',
                    'CAR_NUMBER',
                    'CAR_YEAR',
                    'COLOR' 
                ],
                'order' => $sort['sort'],
            ]);

            $this->arResult['GRID_LIST'] = $this->prepareGridList($books);
        } else {
            $this->arResult['GRID_LIST'] = [];
        }

        $this->arResult['NAV'] = $nav;
        $this->arResult['UI_FILTER'] = $this->getFilterFields();
    }

    private function prepareFilter(array $filterData): array
    {
        $filter = [];
        if (!empty($filterData['COLOR'])) {
            $filter['%COLOR'] = $filterData['COLOR'];
        }
        if (!empty($filterData['MODEL'])) {
            $filter['%MODEL'] = $filterData['MODEL'];
        }

        if (!empty($filterData['NUMBER'])) {
            $filter['%NUMBER'] = $filterData['NUMBER'];
        }

        if (!empty($filterData['BRAND'])) {
            $filter['%BRAND'] = $filterData['BRAND'];
        }  

        return $filter;
    }

    private function prepareGridList(Result $books): array
    {
        $gridList = [];
        $groupedBooks = [];

        while ($book = $books->fetch()) {
            $bookId = $book['ID'];

            if (!isset($groupedBooks[$bookId])) {
                $groupedBooks[$bookId] = [                    
                    'ID' => $book['ID'],
                    'BRAND' => $book['BRAND'],
                    'MODEL' => $book['MODEL'],
                    'CAR_NUMBER' => $book['CAR_NUMBER'],
                    'CAR_YEAR' => $book['CAR_YEAR'],
                    'COLOR' => $book['COLOR'],
                ];
            }            
        }
        
        foreach ($groupedBooks as $book) {
            $gridList[] = [
                'data' => [
                    'ID' => $book['ID'],
                    'BRAND' => $book['BRAND'],
                    'MODEL' => $book['MODEL'],
                    'CAR_NUMBER' => $book['CAR_NUMBER'],
                    'CAR_YEAR' => $book['CAR_YEAR'],
                    'COLOR' => $book['COLOR'],
                ],
                'actions' => $this->getElementActions($book),
            ];
        }
        
        return $gridList;
    }

    private function getFilterFields(): array
    {
        return [
            [
                'id' => 'MODEL',
                'name' => Loc::getMessage('CAR_GRID_CAR_MODEL_LABEL'),
                'type' => 'string',
                'default' => true,
            ],
            [
                'id' => 'NUMBER',
                'name' => Loc::getMessage('CAR_GRID_CAR_NUMBER_LABEL'),
                'type' => 'string',
                'default' => true,
            ],
            [
                'id' => 'COLOR',
                'name' => Loc::getMessage('CAR_GRID_CAR_COLOR_LABEL'),
                'type' => 'string',
                'default' => true,
            ],
        ];
    }

    public function deleteElementAction(int $bookId): array
    {
        $this->errorCollection = new ErrorCollection();
        try {
            // $ormClass = $this->arParams['ORM_CLASS'];
            CarTable::delete($bookId);
        } catch (Exception $e) {
            $this->errorCollection->add([new Error($e->getMessage())]);
        }

        return [];
    }
}
