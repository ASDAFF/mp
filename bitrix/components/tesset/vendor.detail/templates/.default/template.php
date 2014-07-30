<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?$item = $arResult;?>
<style>
    div.vendor-content { width: 960px;}
    div.vendor-content .vendor-header {}
    div.vendor-content .vendor-header div.avatar { display: inline-block;}
    div.vendor-content .vendor-header div.avatar img {}
    div.vendor-content .vendor-header div.vendor-info { display: inline-block; margin-right: 20px;}
    div.vendor-content .vendor-header div.vendor-info h1 {}
    div.vendor-content .vendor-header div.vendor-info p.vendor-anounce {}
    div.vendor-content .vendor-header div.vendor-info div.vendor-links {}
    div.vendor-content .vendor-header div.vendor-info div.vendor-links ul {padding: 0;}
    div.vendor-content .vendor-header div.vendor-info div.vendor-links ul li {list-style: none; display: inline-block; width: 200px;}
    div.vendor-content .vendor-header div.vendor-info div.vendor-links ul li a {text-decoration: none; color: #F15824;}
    div.vendor-content .vendor-header div.vendor-info div.vendor-links ul li a:hover {text-decoration: underline;}
    div.vendor-content .vendor-products {}
    div.vendor-content .vendor-products h2 {margin-left: 16px;}
</style>
<div class="vendor-content">
    <div class="vendor-header">
        <div class="avatar">
            <img src="<?=$item['avatar']['src']?>" border="0"/>
        </div>
        <div class="vendor-info">
            <h1><?=$item['name']?></h1>
            <p class="vendor-anounce">
                <?=$item['anounce']?>
            </p>
            <div class="vendor-links">
                <ul>
                    <?if ($item['vk']) : ?>
                        <li><a href="<?=$item['vk']?>" target="_blank"><span class="fa fa-vk"></span> Профиль VK</a></li>
                    <?endif;?>
                    <?if ($item['fb']) : ?>
                        <li><a href="<?=$item['fb']?>" target="_blank"><span class="fa fa-facebook"></span> Профиль Facebook</a></li>
                    <?endif;?>
                    <?if ($item['city']) : ?>
                        <li><span class="fa fa-bank"></span><?=$item['city']?></li>
                    <?endif;?>
                </ul>
            </div>
        </div>
    </div>
    <div class="vendor-products">
        <?if ($item['globalFilter']) : global $arrFilter; $arrFilter['ID'] = $item['globalFilter']?>
        <h2>Все товары</h2>
        <!-- ТОВАРЫ ПРОИЗВОДИТЕЛЯ -->
        <?$APPLICATION->IncludeComponent("bitrix:catalog.section", "butik_main_anounce", array(
            "IBLOCK_TYPE" => "1c_catalog",
            "IBLOCK_ID" => "1",
            "SECTION_ID" => "",
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "",
            ),
            "ELEMENT_SORT_FIELD" => "",
            "ELEMENT_SORT_ORDER" => "",
            "ELEMENT_SORT_FIELD2" => "",
            "ELEMENT_SORT_ORDER2" => "",
            "FILTER_NAME" => "arrFilter",
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
            "HIDE_NOT_AVAILABLE" => "N",
            "PAGE_ELEMENT_COUNT" => "30",
            "LINE_ELEMENT_COUNT" => "3",
            "PROPERTY_CODE" => array(
                0 => "",
                1 => "TAGS",
                2 => "",
            ),
            "OFFERS_FIELD_CODE" => array(
                0 => "NAME",
                1 => "PREVIEW_TEXT",
                2 => "PREVIEW_PICTURE",
                3 => "",
            ),
            "OFFERS_PROPERTY_CODE" => array(
                0 => "",
                1 => "",
            ),
            "OFFERS_SORT_FIELD" => "",
            "OFFERS_SORT_ORDER" => "",
            "OFFERS_SORT_FIELD2" => "",
            "OFFERS_SORT_ORDER2" => "",
            "OFFERS_LIMIT" => "5",
            "TEMPLATE_THEME" => "",
            "PRODUCT_DISPLAY_MODE" => "N",
            "ADD_PICT_PROP" => "-",
            "LABEL_PROP" => "-",
            "PRODUCT_SUBSCRIPTION" => "N",
            "SHOW_DISCOUNT_PERCENT" => "N",
            "SHOW_OLD_PRICE" => "N",
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "Y",
            "SET_META_KEYWORDS" => "Y",
            "META_KEYWORDS" => "",
            "SET_META_DESCRIPTION" => "Y",
            "META_DESCRIPTION" => "",
            "BROWSER_TITLE" => "-",
            "ADD_SECTIONS_CHAIN" => "N",
            "DISPLAY_COMPARE" => "N",
            "SET_TITLE" => "Y",
            "SET_STATUS_404" => "N",
            "CACHE_FILTER" => "N",
            "PRICE_CODE" => array(
                0 => "Минимальная",
                1 => "Закупочная",
                2 => "Розничная",
            ),
            "USE_PRICE_COUNT" => "N",
            "SHOW_PRICE_COUNT" => "1",
            "PRICE_VAT_INCLUDE" => "Y",
            "CONVERT_CURRENCY" => "N",
            "BASKET_URL" => "/personal/basket.php",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "USE_PRODUCT_QUANTITY" => "N",
            "ADD_PROPERTIES_TO_BASKET" => "Y",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRODUCT_PROPERTIES" => array(
            ),
            "OFFERS_CART_PROPERTIES" => array(
            ),
            "PAGER_TEMPLATE" => "modern",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "Товары",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity"
            ),
            false
        );?>
        <?endif;?>
    </div>
</div>