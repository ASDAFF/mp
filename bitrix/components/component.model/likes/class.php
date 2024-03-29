<?php
/**
 * Сущность элемента
 */
class Likes
{
    const DEFAULT_IBLOCK_ID = 17;

    /**
     * ID инфоблока лайков
     * @var int
     */
    private $iblockId;
    
    public function __construct($iblockId = false) {
        $this->iblockId = (false === $iblockId) ? DEFAULT_IBLOCK_ID : $iblockId;
        CModule::IncludeModule('iblock');
    }

    /**
     * Количество лайков записи блога
     * @param  int $id ID статьи блога
     * @return int     Количество лайков
     */
    public function count($id) {
        $likes = CIBlockElement::GetList(
            false, array(
                'IBLOCK_ID' => 17,
                'PROPERTY_OBJECT_ID' => $id,
                'PROPERTY_IBLOCK_ID' => $this->iblockId,
                'ACTIVE' => 'Y'
                ),
            false, false, 
            array('ID'))->SelectedRowsCount();
        return $likes;
    }

    /**
     * Проверяет лайкнул ли данный пользователь (с данным IP адресом и записью в куки) <br>
     * данную статью блогов
     * @param  int  $id ID статьи блогов
     * @return mixed     FALSE/ID статьи блога
     */
    public function isLikedByCurrent($id) {
        $like = CIBlockElement::GetList(
            false,
            array(
                'IBLOCK_ID' => 17,
                'PROPTERTY_IBLOCK_ID' => $this->iblockId,
                'PROPERTY_OBJECT_ID' => $id,
                'PROPERTY_HASH' => $_COOKIE['muchmore-blog-like'],
                'PROPERTY_IP' => $_SERVER['REMOTE_ADDR'],
                'ACTIVE' => 'Y',
                ),
            false, false, array(
                'ID'
                )
            )->Fetch();

        return (false === $like) ? false : $like['ID'];
    }

}