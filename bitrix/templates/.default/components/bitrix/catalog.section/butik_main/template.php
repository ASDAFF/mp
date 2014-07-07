<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<!-- <pre><?=print_r($arResult)?></pre> -->
<script>
	$(document).ready(function () {
		$('.item-block2').hover(function () {
			$(this).find('div.cabinet-box3').fadeIn();
		}, function () {
			$(this).find('div.cabinet-box3').fadeOut();
		});
	});
</script>

<div class="items2">
<?
	foreach($arResult['ITEMS'] as $arItem) {
		// Проверка товаров на наличие товара и его ТП
		// $opacity = true;
		// if (empty($arItem['OFFERS'])) {
		// 	if ($arItem['CATALOG_QUANTITY'] > 0) {
		// 		$opacity = false;
		// 	}
		// } else {
		// 	foreach ($arItem['OFFERS'] as $offer) {
		// 		if ($offer['CATALOG_QUANTITY'] > 0) {
		// 			$opacity = false;
		// 		}
		// 	}
		// }
?>
	
	<div class="item-block2">
		<?
			$file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>300*1.5, 'height'=>200*1.5), BX_RESIZE_IMAGE_EXACT, true, false, false, 100);
		?>
		<div style="height:200px; width:300px;">
			<a href="/butik/<?=$arItem['CODE']?>/"><img style="width:300px; height:199px;" src="<?=$file['src']?>" alt=""></a>
		</div>				
		<a href="/butik/<?=$arItem['CODE']?>/" class="cat-link"><?=!empty($arItem['PREVIEW_TEXT'])?$arItem['NAME'].': '.$arItem['PREVIEW_TEXT']:$arItem['NAME']?></a>
		<div class="price"><?=(!empty($arItem['OFFERS']))?number_format($arItem['OFFERS'][0]['CATALOG_PRICE_1'], -1, ',', ' ' ):number_format($arItem['CATALOG_PRICE_1'], -1, ',', ' ' );?> р.</div>
		<div class="cabinet-box3" style="float: right; margin-top: -46px; display: none;">
				<ul>
					<li>
						<?if (!$USER->isAuthorized()) : ?>
							<a class="open-reg" style="background: #f15824; width: 160px; box-shadow: none;" href="/personal/">Купить</a>
						<?elseif (empty($arItem['OFFERS'])) : ?>
							<a style="background: #f15824; width: 160px; box-shadow: none;" class="buy-link" href="/butik/<?=$arItem['CODE']?>/?action=BUY&amp;id=<?=$arItem['ID']?>&amp;ELEMENT_CODE=<?=$arItem['CODE']?>">В корзину</a>
						<?else : ?>
							<a style="background: #f15824; width: 160px; box-shadow: none;" href="/butik/<?=$arItem['CODE']?>/?spec=sku">В корзину</a>
						<?endif;?>
					</li>							
				<ul>
		</div>

		<p><?=implode(' / ',$arItem['PROPERTIES']['TAGS']['VALUE']);?></p>
		
	</div>
	
<?	
	}
?>
</div>
<?
	if ($arParams["DISPLAY_BOTTOM_PAGER"])
	{
		?><? echo $arResult["NAV_STRING"]; ?><?
	}

?>