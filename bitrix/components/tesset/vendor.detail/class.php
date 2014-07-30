<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

CBitrixComponent::includeComponentClass("component.model:item");
CBitrixComponent::includeComponentClass("component.model:likes");
/**
* EvrikaBlogList
* 
* Отображение информации о производителе, а также связанных с ним элементов каталога
* 
* @author Roman Morozov <tesset.studio@gmail.com>
* @version 1.0
*/
class VendorDetail extends CBitrixComponent 
{
    
    /**
     * Изначальное значение сортировки
     * @var array
     */
    private $sort = false;

    /**
     * Поля для выбора основных элементов
     * @var array
     */
    private $select = array(
        'ID',
        'IBLOCK_ID',
        'NAME',
        'PREVIEW_PICTURE', //avatar
        'DETAIL_TEXT', // description
        'PROPERTY_VK', //link vk profile
        'PROPERTY_FB', //link fb profile
        'PROPERTY_CITY' 
        );
    
    /**
     * Если не знаешь что за метод, дальше не смотри
     */
    public function executeComponent() 
    {
        if (false === $this->Initialize()) {
            // 404
            return false;
        }
        global $USER;
        global $APPLICATION;
        global $arrFilter;
        $cache = bitrix_sessid_get() . $USER->GetID();
        
        if ($this->startResultCache(0, $cache)) {
            $this->arResult = $this->GetItems();
            $APPLICATION->SetTitle($this->arResult['name']);
            $APPLICATION->SetPageProperty('description', $this->arResult['anounce']);
            $APPLICATION->AddHeadString('<meta property="og:title" content="' . $this->arResult['name'] . '"/>');
            $APPLICATION->AddHeadString('<meta property="og:description" content="' . $this->arResult['anounce'] . '"/>');
            $arrFilter = $this->arResult['globalFilter'];
            $this->includeComponentTemplate();
        }
    }

    /**
     * Initialize main component parameters
     */
    public function Initialize() {
        if (!CModule::IncludeModule("iblock")) {
            return false;
        } 
        if (!$this->arParams["IBLOCK_ID"]) {
            $this->arParams["IBLOCK_ID"] = 3; 
        }
        if (!$this->arParams["ELEMENT_CODE"]) {
            $this->arParams["ELEMENT_CODE"] = $_GET['ELEMENT_CODE'];
        }
        if (!$this->arParams["ELEMENT_CODE"]) {
            return false;
        }
        return true;
    }

    /**
     * Формирование стандартного фильтра.
     * @return array Стандартный фильтр
     */
    private function filter() 
    {
        $filter = array(    
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            'CODE' => $this->arParams['ELEMENT_CODE'],
            'ACTIVE' => 'Y'
        );
        return $filter;
    }

    /**
     * Получение полностью сформированного списка необходимых элементов. <br/>
     * Зависит от self::<b>composeItem(</i>Item</i> instance)</b>
     * @return array Список сформированных элементов
     */
    private function GetItems() 
    {
        return $this->data(CIBlockElement::GetList(
            $this->sort, 
            $this->filterEx(),
            false,
            false, 
            $this->select
        ));

    }

    /**
     * Обработчик результат. Вносим изменения при необходимости
     * @param  CDBResult $rsItems Выборка основного массива элементов
     * @return array              Основной массив элементов
     */
    private function data($rsItems) {
        if ($x = $rsItems->Fetch()) {
            $item = $this->composeItem(new Item($x));
        }
        CIBlockElement::CounterInc($item['id']);
        return $item;
    }

    // --------- ITEM LOGIC -------------- //

    /**
     * Расширенный фильтр для основного GetList() элементов
     * @return array 
     */
    private function filterEx() 
    {
        $filterEx = false; // Почему? потому что бля (см. стандартный класс для элементов)
        $filter = (false === $filterEx) ? $this->filter() : array_merge($this->filter(), $filterEx);
        return $filter;
    }

    
    /**
     * Формирует данные об основном элементе
     * @param  Item   $item Item instance
     * @return array       Данные об элменте
     */
    private function composeItem(Item $item) 
    {
        $x = array(
            'id' => $item->field('ID'),
            'iblockId' => $item->field('IBLOCK_ID'),
            'name' => $item->field('NAME'),
            'avatar' => $item->src('PREVIEW_PICTURE', $resized = array(120, 120)),
            'anounce' => $item->field('DETAIL_TEXT'),
            'vk' => $item->propValue('VK'),
            'fb' => $item->propValue('FB'),
            'city' => $item->propValue('CITY'),
            'shows' => ($item->field('SHOW_COUNTER')) ? $item->field('SHOW_COUNTER') : 0
            );
        $x['globalFilter'] = $this->products($x['id']);
        return $x;
    }

    /**
     * Поиск товаров привязанных к данному производителю по тегу
     * @param  int $seller ID производителя
     * @return array         Массив ID товаров
     */
    public function products($seller) {
        $rs = CIBlockElement::GetList(false , array(
            'IBLOCK_ID' => 1,
            'ACTIVE' => 'Y',
            'PROPERTY_SELLER' => $seller
            ), false, false, array(
            'ID'
            ));
        while ($x = $rs->Fetch()) {
            $ids[] = $x['ID'];
        }
        return $ids;
    }
}
