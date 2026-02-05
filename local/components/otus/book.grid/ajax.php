<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Otus\Orm\BookTable;
use Otus\Orm\CarTable;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Error;

class BookGridAjaxController extends \Bitrix\Main\Engine\Controller
{
    public function configureActions(): array
    {
        return [
            'deleteElement' => [
                'prefilters' => [],
            ],
            'addBook' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
        ];
    }

    public function addBookAction(): array
    {
        try {
            $bookTitle = $this->request->get('bookTitle');

            if (empty($bookTitle)) {
                $this->errorCollection->add([ new Error('Не передано название')]);
                return [];
            }

            $addResult = CarTable::add([
                'BRAND' => 'Lada',
                'MODEL' => 'Vesta',
                'CAR_NUMBER' => 'CK718M37',
                'CAR_YEAR' => (new Date)->add('-3Y'),
                'MILEAGE' => 500,
                'COLOR' => 'Чёрный',
            ]);

            if ($addResult->isSuccess()) {
                $result['BOOK_ID'] = $addResult->getId();
            } else {
                $this->errorCollection->add($addResult->getErrorMessages());
                return [];
            }
        } catch (\Exception $e) {
            $this->errorCollection->add([new Error($e->getMessage())]);
            return [];
        }

        return $result;
    }

    public function deleteElementAction(int $bookId): array
    {
        $result = [];

        try {
            $deleteResult = BookTable::delete($bookId);

            if ($deleteResult->isSuccess()) {
                return $result;
            } else {
                $this->errorCollection->add($deleteResult->getErrorMessages());
                return [];
            }

        } catch (\Exception $e) {
            $this->errorCollection->add([new Error($e->getMessage())]);
            return [];
        }
    }
}