<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");
?><?$APPLICATION->IncludeComponent("bitrix:sale.order.ajax", ".default", array(
	"PAY_FROM_ACCOUNT" => "Y",
	"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
	"COUNT_DELIVERY_TAX" => "N",
	"ALLOW_AUTO_REGISTER" => "N",
	"SEND_NEW_USER_NOTIFY" => "Y",
	"DELIVERY_NO_AJAX" => "N",
	"DELIVERY_NO_SESSION" => "N",
	"TEMPLATE_LOCATION" => "popup",
	"DELIVERY_TO_PAYSYSTEM" => "d2p",
	"USE_PREPAYMENT" => "N",
	"ALLOW_NEW_PROFILE" => "N",
	"SHOW_PAYMENT_SERVICES_NAMES" => "Y",
	"SHOW_STORES_IMAGES" => "N",
	"PATH_TO_BASKET" => "",
	"PATH_TO_PERSONAL" => "/personal/",
	"PATH_TO_PAYMENT" => "/payment/",
	"PATH_TO_AUTH" => "/personal/",
	"SET_TITLE" => "Y",
	"PRODUCT_COLUMNS" => array(
		0 => "PREVIEW_TEXT",
	),
	"DISABLE_BASKET_REDIRECT" => "N"
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>