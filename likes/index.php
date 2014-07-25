<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$wishUser = CUser::GetByLogin($_GET['USER'])->Fetch();
$APPLICATION->SetTitle("Muchmore.ru - Понравилось пользователю " . $wishUser['NAME']);
$APPLICATION->AddHeadString('<meta property="og:title" content="Muchmore.ru - Понравилось пользователю ' . $wishUser['NAME'] . '"/>');
CModule::IncludeModule('iblock');
$wishUser = CUser::GetByLogin($_GET['USER'])->Fetch();
if (false === $wishUser) {

} else {
	$rsItems = CIBlockElement::GetList(array(
		'DATE_CREATE' => 'DESC'
		), array(
		'IBLOCK_ID' => 17,
		'PROPERTY_USER' => $wishUser['ID'],
		'ACTIVE' => 'Y'
		), false, false, array(
		'PROPERTY_OBJECT_ID'
		));
	while ($item = $rsItems->Fetch()) {
		$items[] = $item['PROPERTY_OBJECT_ID_VALUE'];
	}
}
	require_once($_SERVER['DOCUMENT_ROOT']  . '/butik/.tags.class.php');
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
	<?=$tags->getName();?>
	<div style="clear:both;"></div>
	<?=$tags->subTags();?>
	<div style="clear:both;"></div>
	<?=$tags->getBrands();?>
	<h3 style="margin-top: 88px; margin-left: 18px; font-weight: 500; font-family: "Open Sans",sans-serif;">Понравилось пользователю <?=$wishUser['NAME']?></h3>
	<div class="soc" style="margin: -44px 0px -39px -20px; float: right;">
		<ul>
			
			<li>
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&appId=681312905274770&version=v2.0";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
				<div class="fb-share-button" data-href="<?=$APPLICATION->GetCurDir();?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
				<!-- <div class="fb-like" data-href="<?=$APPLICATION->GetCurDir();?>" data-width="22" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div> -->
			</li>
			<li>
				<div id="ok_shareWidget"></div>
					<script>
					!function (d, id, did, st) {
					  var js = d.createElement("script");
					  js.src = "http://connect.ok.ru/connect.js";
					  js.onload = js.onreadystatechange = function () {
					  if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
					    if (!this.executed) {
					      this.executed = true;
					      setTimeout(function () {
					        OK.CONNECT.insertShareWidget(id,did,st);
					      }, 0);
					    }
					  }};
					  d.documentElement.appendChild(js);
					}(document,"ok_shareWidget","<?=$APPLICATION->GetCurDir()?>","{width:160,height:25,st:'rounded',sz:12,ck:2}");
					</script>

			</li>
			<li style="width:110px;">
				<!-- Put this script tag to the <head> of your page -->
				<script type="text/javascript" src="http://vk.com/js/api/share.js?90" charset="windows-1251"></script>

				<!-- Put this script tag to the place, where the Share button will be -->
				<script type="text/javascript"><!--
				document.write(VK.Share.button(false,{type: "round", text: "Поделиться"}));
				--></script>
				</script>
			</li>
			<li>
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$APPLICATION->GetCurDir();?>" data-text="Muchmore" data-via="muchmore" data-lang="ru" data-related="muchmore" data-hashtags="muchmore">Твитнуть</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			</li>

			<!-- <li style="width: 160px;">
				<?if ($arResult['wish']['isWished']) : ?>
					<a href="javascript:;" class="wish-item del" data-object="<?=$arResult['ID']?>">Удалить из Wishlist</a></li>
				<?else : ?>
					<a href="javascript:;" class="wish-item" data-object="<?=$arResult['ID']?>">Добавить в Wishlist</a></li>
				<?endif;?>
			<li style="width: 160px;"><span class="fa fa-heart-o blog-item-like blog-item-active-info <?=($arResult['likes']['already_liked']) ? 'active-like' : ''?>" data-object="<?=$arResult['ID']?>" data-ib="<?=$arResult['IBLOCK_ID']?>"> <?=$arResult['likes']['value']?></span></li> -->
		</ul>
	</div>
<?if ($items) : $arrFilter['ID'] = $items;?>
<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "butik_main", array(
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
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>