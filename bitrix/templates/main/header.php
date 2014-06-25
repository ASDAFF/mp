<!DOCTYPE html>
<html>
<head>
	<?$APPLICATION->ShowHead();?>
	<title><?$APPLICATION->ShowTitle()?></title>
	<style type="text/css">
		.main-page{
			width:1000px;
			margin: 100px auto;
		}
	</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?$APPLICATION->ShowPanel()?>
<div class="main-page">
<?$APPLICATION->IncludeComponent("bitrix:menu", "catalog_horizontal", array(
	"ROOT_MENU_TYPE" => "top",
	"MENU_THEME" => "site",
	"MENU_CACHE_TYPE" => "N",
	"MENU_CACHE_TIME" => "3600",
	"MENU_CACHE_USE_GROUPS" => "Y",
	"MENU_CACHE_GET_VARS" => array(
	),
	"MAX_LEVEL" => "1",
	"CHILD_MENU_TYPE" => "left",
	"USE_EXT" => "N",
	"DELAY" => "N",
	"ALLOW_MULTI_SELECT" => "N"
	),
	false
);?><br>
<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", ".default", array(
	"PATH_TO_BASKET" => "/cart/",
	"PATH_TO_ORDER" => "",
	"SHOW_DELAY" => "Y",
	"SHOW_NOTAVAIL" => "Y",
	"SHOW_SUBSCRIBE" => "Y"
	),
	false
);?><br />
<?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"",
	Array(
	)
);?><br>