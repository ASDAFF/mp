<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

CBitrixComponent::includeComponentClass("component.model:item");
CBitrixComponent::includeComponentClass("component.model:likes");
/**
* CRelatedSKU
* 
* Связанные товары по ID.
* Велючает в себя получение информации 
* только о Торговых предложениях по товару либо торговому предложению
* 
* @author Roman Morozov <morozov@newsite.by>
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
        'IBLOCK_ID',
        'NAME',
        'DETAIL_PICTURE',
        'DETAIL_TEXT',
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
            // 404
            return false;
        }
        global $USER;
        global $APPLICATION;
        $cache = $this->navParams . bitrix_sessid_get() . $USER->GetID();
        
        if ($this->startResultCache(0, $cache)) {
            $this->arResult = $this->GetItems();
            $APPLICATION->SetTitle($this->arResult['name']);
            $APPLICATION->SetPageProperty('description', $this->arResult['anounce']);
            $APPLICATION->AddHeadString('<meta property="og:title" content="' . $this->arResult['name'] . '"/>');
            $APPLICATION->AddHeadString('<meta property="og:description" content="' . $this->arResult['anounce'] . '"/>');
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
        if (!CModule::IncludeModule('prmedia.treelikecomments')) {
            return false;
        }
        if (!$this->arParams["IBLOCK_ID"]) {
            $this->arParams["IBLOCK_ID"] = 14; 
        }
        if (!$this->arParams["ELEMENT_CODE"]) {
            $this->arParams["ELEMENT_CODE"] = $_GET['ELEMENT_CODE'];
        }
        if (!$this->arParams["ELEMENT_CODE"]) {
            return false;
        }
        $this->likes = new Likes($this->arParams["IBLOCK_ID"]);
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
        if ($item = $rsItems->GetNext()) {
            $items = $this->composeItem(new Item($item));
        }
        CIBlockElement::CounterInc($items['id']);
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
            'iblockId' => $item->field('IBLOCK_ID'),
            'name' => $item->field('NAME'),
            'picture' => $item->src('DETAIL_PICTURE', $resized = false),
            'text' => $item->field('DETAIL_TEXT'),
            'anounce' => $item->field('PREVIEW_TEXT'),
            'date' => ConvertDateTime($item->field('DATE_CREATE'), 'DD.MM.YYYY'),
            'related' => $item->propValue('RELATED'),
            'shows' => ($item->field('SHOW_COUNTER')) ? $item->field('SHOW_COUNTER') : 0
            );
        $x['likes'] = array(
            'value' => $this->likes->count($x['id']),
            'already_liked' => $this->likes->isLikedByCurrent($x['id'])
            );
        $x['comments'] = $this->comments($x['id']);
        return $x;
    }

    /**
     * Количество комментариев записи блога
     * @param  int $id ID статьи блога
     * @return int     Количество комментариев
     */
    public function comments($id) {
        $comments = CTreelikeComments::GetList(
            array("ID" => "DESC"), 
            array("OBJECT_ID_NUMBER" => $id)
        )->SelectedRowsCount();
        return $comments;
    }
}
