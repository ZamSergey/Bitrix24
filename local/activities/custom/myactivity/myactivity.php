<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Bizproc\Activity\BaseActivity;
use Bitrix\Bizproc\FieldType;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\Activity\PropertiesDialog;
use Bitrix\Main\Loader;

class CBPMyActivity extends BaseActivity
{
    // protected static $requiredModules = ["crm"];
    
    /**
     * @see parent::_construct()
     * @param $name string Activity name
     */
    public function __construct($name)
    {
        parent::__construct($name);

        $this->arProperties = [
            'Inn' => '',

            // return
            'Text' => null,
        ];

        $this->SetPropertiesTypes([
            'Text' => ['Type' => FieldType::STRING],
        ]);
    }

    /**
     * Return activity file path
     * @return string
     */
    protected static function getFileName(): string
    {
        return __FILE__;
    }

    public function getDataInfo(): Array
    {
        $rootActivity = $this->GetRootActivity();
        $token = $rootActivity->GetConstant("TOKEN"); 
        $secret =  $rootActivity->GetConstant("SECRET");         
        
        $dadata = new Dadata($token, $secret);
        $dadata->init();

        $fields = array("query" => $this->Inn, "count" => 5);
        $response = $dadata->suggest("party", $fields);

        return $response;
    }
    /**
     * @return ErrorCollection
     */
    protected function internalExecute(): ErrorCollection 
    {
        $errors = parent::internalExecute(); 

        // $token = "f0ad26ce162a41c4907caafa144e1c3dd598f148";
        // $secret = "0877bb4ccbdf964997920ab4818e40889b91fb81";    
        
        $response = $this->getDataInfo();
        
        $companyName = 'Компания не найдена!';
        if(!empty($response['suggestions'])){ // если копания найдена
           // по ИНН возвращается массив в котором может бытьнесколько элементов (компаний)
           $companyName = $response['suggestions'][0]['value']; // получаем имя компании из первого элемента  
           // id ответственного и уведомителя
            $responsible = 1;

            // создаем компанию
            $arNewCompany = array(
                "TITLE" => $companyName,
                "OPENED" => "Y",
                "COMPANY_TYPE" => "CUSTOMER",
                "ASSIGNED_BY_ID" => $responsible,
                'UF_COMPANY_INN' => $this->Inn
            );
            
        
            $company = new CCrmCompany(false);
            $companyID = $company->Add($arNewCompany);
        }  

        
        
        // $this->log(print_r( $response, true));
        // $this->log(print_r($this->Inn, true));
        // \Bitrix\Main\Loader::includeModule('crm');


        


        
        $rootActivity = $this->GetRootActivity(); // получаем объект активити
        // сохранение полученных результатов работы активити в переменную бизнес процесса
        // $rootActivity->SetVariable("TEST", $this->preparedProperties['Text']); 

        // получение значения полей документа в активити        
        $documentType = $rootActivity->getDocumentType(); // получаем тип документа
        $documentId = $rootActivity->getDocumentId(); // получаем ID документа 

        // // получаем объект документа над которым выполняется БП (элемент сущности Компания)
        // $documentService = CBPRuntime::GetRuntime(true)->getDocumentService(); 
        // // $documentService = $this->workflow->GetService("DocumentService");   

        // // поля документа
        // $documentFields =  $documentService->GetDocumentFields($documentType);
        // //$arDocumentFields = $documentService->GetDocument($documentId);   

        // $arr = [];
        // foreach ($documentFields as $key => $value) {
        //     $arr[] = $key;
        //     if($key == 'PROPERTY_Z_INN'){ // поле номер ИНН
        //         $fieldValue = $documentService->getFieldValue($documentId, $key, $documentType);
        //         // $this->log('значение поля Инн:'.' '.$fieldValue);
        //     }

        //     if($key == 'UF_COMPANY_INN'){ // поле UF_COMPANY_INN
        //         $fieldValue = $documentService->getFieldValue($documentId, $key, $documentType);
        //         // $this->log('значение поля UF_COMPANY_INN:'.' '.$fieldValue);
        //     }
        // }

        

        list($type, $blockId) = explode("_", $documentType[2]);

        if($type === "iblock") {
            CIBlockElement::SetPropertyValuesEx( $documentId[2], $blockId, [
                "Z_NAME" => $companyName,            
            ]);
        }
            
       

        $this->log(print_r( $errors, true));
      
        

        return $errors;
    }

    /**
     * @param PropertiesDialog|null $dialog
     * @return array[]
     */
    public static function getPropertiesDialogMap(?PropertiesDialog $dialog = null): array
    {
        $map = [
            'Inn' => [
                'Name' => Loc::getMessage('SEARCHBYINN_ACTIVITY_FIELD_SUBJECT'),
                'FieldName' => 'inn',
                'Type' => FieldType::STRING,
                'Required' => true,
                'Options' => [],
            ],
            'Test field' => [
                'Name' => Loc::getMessage('TEST_FIELD_TEXT'),
                'FieldName' => 'test',
                'Type' => FieldType::STRING,
                'Required' => false,
                'Options' => [],
            ],
        ];
        return $map;
    }




}
