<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Товар");
?>

	<?
	require_once('.tags.class.php');
	$tags = new WRTags();
	?>
	<link rel="stylesheet" type="text/css" href="/src/css/butik.css" />
	<div class="cat-menu">
		<ul class="sf-menu">
			<li><? $tags->drawCatalog();?></li>
			<li><? $tags->drawGifts();?></li>
			<li><? $tags->drawFacility();?></li>			
		</ul>
		<div class="cat-phone">
			<p>т: 8-495-517-43-64</p>
			<p>Мегафон, Москва</p>
		</div>
	</div>
	<? $tags->setFilter();?>
	<div style="clear:both;"></div>
	<?if ($_GET['spec'] == 'sku') : ?>
	<div style="width: 75%; height: 30px; padding: 1px 0 9px 28px; color: #f15824; font-size: 15px;">
		<p>Товар содержит характеристики. Пожалуйста, выберете какой именно вы покупаете.</p>
	</div>
	<?endif;?>

<?$APPLICATION->IncludeComponent("bitrix:catalog.element", "butik-tovar", array(
	"IBLOCK_TYPE" => "1c_catalog",
	"IBLOCK_ID" => "1",
	"ELEMENT_ID" => "",
	"ELEMENT_CODE" => $_REQUEST["ELEMENT_CODE"],
	"SECTION_ID" => "",
	"SECTION_CODE" => "",
	"HIDE_NOT_AVAILABLE" => "N",
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"OFFERS_FIELD_CODE" => array(
		0 => "NAME",
		1 => "",
	),
	"OFFERS_PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"OFFERS_SORT_FIELD" => "",
	"OFFERS_SORT_ORDER" => "",
	"OFFERS_SORT_FIELD2" => "",
	"OFFERS_SORT_ORDER2" => "",
	"OFFERS_LIMIT" => "0",
	"TEMPLATE_THEME" => "",
	"ADD_PICT_PROP" => "-",
	"LABEL_PROP" => "-",
	"OFFER_ADD_PICT_PROP" => "-",
	"OFFER_TREE_PROPS" => array(
	),
	"DISPLAY_NAME" => "Y",
	"DETAIL_PICTURE_MODE" => "IMG",
	"ADD_DETAIL_TO_SLIDER" => "N",
	"DISPLAY_PREVIEW_TEXT_MODE" => "H",
	"PRODUCT_SUBSCRIPTION" => "N",
	"SHOW_DISCOUNT_PERCENT" => "N",
	"SHOW_OLD_PRICE" => "N",
	"SHOW_MAX_QUANTITY" => "N",
	"DISPLAY_COMPARE" => "N",
	"MESS_BTN_BUY" => "Купить",
	"MESS_BTN_ADD_TO_BASKET" => "В корзину",
	"MESS_BTN_SUBSCRIBE" => "Подписаться",
	"MESS_BTN_COMPARE" => "Сравнение",
	"MESS_NOT_AVAILABLE" => "Нет в наличии",
	"USE_VOTE_RATING" => "N",
	"USE_COMMENTS" => "N",
	"BRAND_USE" => "N",
	"SECTION_URL" => "",
	"DETAIL_URL" => "",
	"SECTION_ID_VARIABLE" => "SECTION_ID",
	"CACHE_TYPE" => "N",
	"CACHE_TIME" => "36000000",
	"CACHE_GROUPS" => "Y",
	"META_KEYWORDS" => "-",
	"META_DESCRIPTION" => "-",
	"BROWSER_TITLE" => "-",
	"SET_TITLE" => "Y",
	"SET_STATUS_404" => "N",
	"ADD_SECTIONS_CHAIN" => "Y",
	"ADD_ELEMENT_CHAIN" => "N",
	"USE_ELEMENT_COUNTER" => "Y",
	"PRICE_CODE" => array(
		0 => "Розничная",
	),
	"USE_PRICE_COUNT" => "N",
	"SHOW_PRICE_COUNT" => "1",
	"PRICE_VAT_INCLUDE" => "Y",
	"PRICE_VAT_SHOW_VALUE" => "N",
	"CONVERT_CURRENCY" => "N",
	"BASKET_URL" => "/cart/",
	"ACTION_VARIABLE" => "action",
	"PRODUCT_ID_VARIABLE" => "id",
	"USE_PRODUCT_QUANTITY" => "Y",
	"PRODUCT_QUANTITY_VARIABLE" => "quantity",
	"ADD_PROPERTIES_TO_BASKET" => "Y",
	"PRODUCT_PROPS_VARIABLE" => "prop",
	"PARTIAL_PRODUCT_PROPERTIES" => "Y",
	"PRODUCT_PROPERTIES" => array(
	),
	"OFFERS_CART_PROPERTIES" => array(
	),
	"LINK_IBLOCK_TYPE" => "",
	"LINK_IBLOCK_ID" => "",
	"LINK_PROPERTY_SID" => "",
	"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#"
	),
	false
);?>	
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>