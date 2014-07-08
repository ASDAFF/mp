<?php
/**
 * Абстрактный класс для всех сущностей CIBlockElement::GetList()
 */
abstract class AbstractItem 
{
    /**
     * Массив элемента
     * @var array
     */
    protected $item;

    /**
     * Ресурс файла
     * @param  string  $field   Название поля для ресурса
     * @param  mixed $resized   Если необходим ресайзер
     * @return string           Ресурс
     */
    public function src($field, $resized = false) 
    {
        if (false === $resized) {
            $src['src'] = CFile::GetPath($this->item[$field]);
        } else {
            $src = CFile::ResizeImageGet($this->item[$field], array("width" => $resized[0], "height" => $resized[0] ), BX_RESIZE_IMAGE_EXACT, false);
        }
        return $src;
    }

    /**
     * Значение свойства по кода
     * @param  string $code Символьный код свойства
     * @return mixed        Значение свойства
     */
    public function propValue($code) 
    {
        return $this->item['PROPERTY_' . $code . '_VALUE'];
    }

    /**
     * Return any field value by field name
     * @param  strign $fieldName Field name
     * @return mixed            Value of the field
     */
    public function field($fieldName) 
    {
        return $this->item[$fieldName];
    }

    /**
     * Full data of the item
     * @return array Full item data
     */
    public function data() {
        return $this->item;
    }

    /**
     * Price in any format
     * @return mixed Price
     */
    abstract public function price($price);
       
}