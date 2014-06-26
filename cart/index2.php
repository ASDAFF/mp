<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
?><?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket", ".default", array(
	"COLUMNS_LIST" => array(
		0 => "TYPE",
	),
	"PATH_TO_ORDER" => "/personal/order.php",
	"HIDE_COUPON" => "N",
	"PRICE_VAT_SHOW_VALUE" => "N",
	"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
	"USE_PREPAYMENT" => "N",
	"QUANTITY_FLOAT" => "N",
	"SET_TITLE" => "Y",
	"ACTION_VARIABLE" => "action",
	"OFFERS_PROPS" => array(
	)
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>