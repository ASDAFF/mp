<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

CBitrixComponent::includeComponentClass("component.model:item");
CBitrixComponent::includeComponentClass("component.model:likes");

/**
* EvrikaBlogList
* 
* @author Roman Morozov <tesset.studio@gmail.com>
* @version 1.0
*/
class EvrikaBlogList extends CBitrixComponent 
{
    
    /**
     * Изначальное значение сортировки
     * @var array
     */
    private $sort = array(
        'DATE_CREATE' => 'DESC'
        );

    /**
     * Поля для выбора основных элементов
     * @var array
     */
    private $select = array(
        'ID',
        'NAME',
        'PREVIEW_PICTURE',
        'PREVIEW_TEXT',
        'SHOW_COUNTER',
        'DATE_CREATE',
        'PROPERTY_LIKES',
        'PROPERTY_RELATED',
        'DETAIL_PAGE_URL'
        );

    /**
     * Параметры для Битриксовой навигации
     * @var array
     */
    private $navParams = array();
    private $likes;

    /**
     * Если не знаешь что за метод, дальше не смотри
     */
    public function executeComponent() 
    {
        if (false === $this->Initialize()) {
            return false;
        }
        global $USER;
        $cache = $this->navParams . bitrix_sessid_get() . $USER->GetID();
        
        if ($this->startResultCache(0, $cache)) {
            $this->arResult["items"] = $this->GetItems();
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
            $this->arParams["IBLOCK_ID"] = 14; 
        }
        if (!$this->arParams["ITEMS_COUNT"]) {
            $this->arParams["ITEMS_COUNT"] = 20;
            $this->navParams = array(
                "nPageSize" => $this->arParams["ITEMS_COUNT"],
            );
        } else {
            $this->navParams = array(
                "nTopCount" => $this->arParams["ITEMS_COUNT"],
            );
        }
        $this->likes = new Likes();
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
            $this->navParams, 
            $this->select
        ));

    }

    /**
     * Обработчик результат. Вносим изменения при необходимости
     * @param  CDBResult $rsItems Выборка основного массива элементов
     * @return array              Основной массив элементов
     */
    private function data($rsItems) {
        while ($item = $rsItems->GetNext()) {
            $items[] = $this->composeItem(new Item($item));
        }
        return $items;
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
            'name' => $item->field('NAME'),
            'picture' => $item->src('PREVIEW_PICTURE', $resized = false),
            'anounce' => $item->field('PREVIEW_TEXT'),
            'date' => ConvertDateTime($item->field('DATE_CREATE'), 'DD.MM.YYYY'),
            'url' => $item->field('DETAIL_PAGE_URL'),
            'related' => $item->propValue('RELATED'),
            'shows' => ($item->field('SHOW_COUNTER')) ? $item->field('SHOW_COUNTER') : 0
            );
        $x['likes'] = $this->likes->count($x['id']);
        return $x;
    }
}
