<?php
CBitrixComponent::includeComponentClass("component.model:abstractItem");
/**
 * Сущность элемента
 */
class Item extends AbstractItem 
{
    
    public function __construct($item) 
    {
        if (!$item) {
            return false;
        }
        $this->item = $item;
    }

    /**
     * Обрезка текста последним пробелом с добавлнием троеточия
     * @param  strign  $text Большой текст для обрезки
     * @param  integer $size Максимальный размер обрезанного текста
     * @return strging       Обрезанный текст
     */
    public function cutText($text, $size = 150) 
    {
        if (strlen($text) > $size) {
            $text = substr(\HTMLToTxt($text), 0, strripos(
                substr(\HTMLToTxt($text), 0, $size ), " "
                )) . "...";
        }
        return $text;
    }

    /**
     * {@inheritdoc}
     */
    public function price($price) {
        return format_byr_price($price);
    }

}