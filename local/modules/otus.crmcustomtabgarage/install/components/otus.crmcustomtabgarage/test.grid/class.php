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
Loader::includeModule('crm');
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
        ];
    }

    private function getElementActions(array $fields): array
    {
        return [
            
            [
                'onclick' => sprintf('BX.Otus.TestGrid.showCarDeal(%d)', $fields['ID']), // метод обработчик в js
                'text' => Loc::getMessage('CAR_GRID_SHOW_CAR_INFO', [
                    
                ]),
                'default' => true,
            ],
            [
                'onclick' => sprintf('BX.Otus.TestGrid.deleteCar(%d)', $fields['ID']),
                'text' => Loc::getMessage('CAR_GRID_DELETE'),
                'default' => true,
            ],
            [
                'onclick' => "window.open('http://192.168.1.185/bitrix/admin/perfmon_row_edit.php?lang=ru&table_name=car2&pk%5BID%5D={$fields['ID']}')", // метод обработчик в js
                'text' => Loc::getMessage('CAR_GRID_OPEN_CAR', [
                    
                ]),
                'default' => true,
            ],
            [
                'onclick' => sprintf('BX.Otus.TestGrid.showFormUpdate(%d)', $fields['ID']), // метод обработчик в js
                'text' => Loc::getMessage('CAR_GRID_UPDATE_CAR', [
                    
                ]),
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
            [
                'id' => 'MILEAGE',
                'name' => Loc::getMessage('CAR_GRID_CAR_MILEAGE_LABEL'),
                'sort' => 'MILEAGE',
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
                    'COLOR',
                    'MILEAGE'
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

        if (!empty($filterData['CAR_NUMBER'])) {
            $filter['%CAR_NUMBER'] = $filterData['CAR_NUMBER'];
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
                    'MILEAGE' => $book['MILEAGE'],
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
                    'MILEAGE' => $book['MILEAGE'],
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
                'id' => 'CAR_NUMBER',
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

    public function getCarinfoAction(int $bookId): array
    {
        $this->errorCollection = new ErrorCollection();
        try {
            $query = CarTable::query()
                ->setSelect([
                    'ID',
                    'BRAND',
                    'MODEL',
                    'CAR_NUMBER',                    
                    'CAR_YEAR',                    
                    'COLOR',
                    'MILEAGE',
                    'CLIENT_ID',
                    'CLIENT_NAME' => 'CLIENT.NAME',
                    'CLIENT_LAST_NAME' => 'CLIENT.LAST_NAME',                    
                ])
                ->setFilter(['=ID' => $bookId]);
            
           
            
            $car = $query->exec()->fetch();
            
            if (!$car) {
                return "not";
                throw new \Exception("Автомобиль с ID $bookId не найден");
            }
            
            // Формируем результат           
            
            $result = [
                
              
                    'car' => [
                        'id' => $car['ID'],
                        'brand' => $car['BRAND'],
                        'model' => $car['MODEL'],
                        'car_number' => $car['CAR_NUMBER'],                       
                        'year' => $car['CAR_YEAR'],                       
                        'color' => $car['COLOR'],
                        'mileage' => $car['MILEAGE'],
                        'client_id' => $car['CLIENT_ID'],
                    ],
                    'client' => [
                        'id' => $car['CLIENT_ID'],
                        'name' => $car['CLIENT_NAME'],
                        'email' => $car['CLIENT_EMAIL'] ?? '',
                        'phone' => $car['CLIENT_PHONE'] ?? '',
                        'full_name' => $car['CLIENT_NAME'] . ' ' . $car['CLIENT_LAST_NAME'] ,
                    ]
                
            ];

             $deals = CCrmDeal::GetListEx(
                ['DATE_CREATE' => 'DESC'], // Сортировка
                [
                    'UF_CAR_ID' => $bookId,       // Фильтр по пользовательскому полю
                    'CHECK_PERMISSIONS' => 'N' // Игнорировать права доступа
                ],
                false, // Группировка
                false, // Навигация
                [
                    'ID',
                    'TITLE',
                    'DATE_CREATE',
                    'STAGE_ID',
                    'ASSIGNED_BY_ID',
                    'OPPORTUNITY',
                    'UF_CAR_ID'
                ]
            );

            $resultDeals = [];

            while ($deal = $deals->Fetch()) {
                $dealId = $deal['ID'];
                
                // Получаем прикрепленные товары
                $productRows = CCrmProductRow::LoadRows('D', $dealId); // 'D' - тип сделка
                $products = [];
                
                foreach ($productRows as $productRow) {
                    if (!empty($productRow['PRODUCT_NAME']) && !empty($productRow['QUANTITY'])) {
                        $products[] = $productRow['PRODUCT_NAME'] . ' '. $productRow['QUANTITY'];
                    }
                    
                }
                
                // Получаем информацию об ответственном
                $assignedBy = null;
                if ($deal['ASSIGNED_BY_ID'] > 0) {
                    $user = CUser::GetByID($deal['ASSIGNED_BY_ID'])->Fetch();
                    if ($user) {
                        $assignedBy = [
                            'ID' => $user['ID'],
                            'FULL_NAME' => trim($user['LAST_NAME'] . ' ' . $user['NAME'] . ' ' . $user['SECOND_NAME']),
                            'PROFILE_LINK' => CComponentEngine::MakePathFromTemplate(
                                '/company/personal/user/#user_id#/',
                                ['user_id' => $user['ID']]
                            )
                        ];
                    }
                }
                
                // Получаем название стадии
                $stageName = '';
                $stageList = CCrmStatus::GetStatusList('DEAL_STAGE');
                if (isset($stageList[$deal['STAGE_ID']])) {
                    $stageName = $stageList[$deal['STAGE_ID']];
                }
                
                // Формируем результат
                $resultDeals[] = [
                    'ID' => $dealId,
                    'TITLE' => $deal['TITLE'],
                    'DATE_CREATE' => $deal['DATE_CREATE'],
                    'STAGE_ID' => $deal['STAGE_ID'],
                    'STAGE_NAME' => $stageName,
                    'ASSIGNED_BY' => $assignedBy,
                    'OPPORTUNITY' => $deal['OPPORTUNITY'],
                    'PRODUCTS' => $products
                ];
            }

            $ressult['dealinfo'] = $resultDeals;
            
        } catch (\Exception $e) {
            $this->errorCollection->add([new Error($e->getMessage())]);
            $result = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
        $result['deals'] = self::getCardealinfoAction($bookId);

        return $result;
    }

    public function getCardealinfoAction (int $carId): array
    {
        $this->errorCollection = new ErrorCollection();

        try {
            $deals = CCrmDeal::GetListEx(
                ['DATE_CREATE' => 'DESC'], // Сортировка
                [
                    'UF_CAR_ID' => $carId,       // Фильтр по пользовательскому полю
                    'CHECK_PERMISSIONS' => 'N' // Игнорировать права доступа
                ],
                false, // Группировка
                false, // Навигация
                [
                    'ID',
                    'TITLE',
                    'DATE_CREATE',
                    'STAGE_ID',
                    'ASSIGNED_BY_ID',
                    'OPPORTUNITY',
                    'UF_CAR_ID'
                ]
            );

            $resultDeals = [];

            while ($deal = $deals->Fetch()) {
                $dealId = $deal['ID'];
                
                // Получаем прикрепленные товары
                $productRows = CCrmProductRow::LoadRows('D', $dealId); // 'D' - тип сделка
                $products = [];
                
                foreach ($productRows as $productRow) {
                    if (!empty($productRow['PRODUCT_NAME']) && !empty($productRow['QUANTITY'])) {
                        $products[] = $productRow['PRODUCT_NAME'] . ' '. $productRow['QUANTITY'];
                    }
                    
                }
                
                // Получаем информацию об ответственном
                $assignedBy = null;
                if ($deal['ASSIGNED_BY_ID'] > 0) {
                    $user = CUser::GetByID($deal['ASSIGNED_BY_ID'])->Fetch();
                    if ($user) {
                        $assignedBy = [
                            'ID' => $user['ID'],
                            'FULL_NAME' => trim($user['LAST_NAME'] . ' ' . $user['NAME'] . ' ' . $user['SECOND_NAME']),
                            'PROFILE_LINK' => CComponentEngine::MakePathFromTemplate(
                                '/company/personal/user/#user_id#/',
                                ['user_id' => $user['ID']]
                            )
                        ];
                    }
                }
                
                // Получаем название стадии
                $stageName = '';
                $stageList = CCrmStatus::GetStatusList('DEAL_STAGE');
                if (isset($stageList[$deal['STAGE_ID']])) {
                    $stageName = $stageList[$deal['STAGE_ID']];
                }
                
                // Формируем результат
                $resultDeals[] = [
                    'ID' => $dealId,
                    'TITLE' => $deal['TITLE'],
                    'DATE_CREATE' => $deal['DATE_CREATE'],
                    'STAGE_ID' => $deal['STAGE_ID'],
                    'STAGE_NAME' => $stageName,
                    'ASSIGNED_BY' => $assignedBy,
                    'OPPORTUNITY' => $deal['OPPORTUNITY'],
                    'PRODUCTS' => $products
                ];
            }
        }
        catch (\Exception $e) {
            $this->errorCollection->add([new Error($e->getMessage())]);
            $result = [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }

        return  $resultDeals;

    }
}
