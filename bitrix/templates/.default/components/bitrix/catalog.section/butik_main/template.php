<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>

<!-- <pre><?=print_r($arResult)?></pre> -->

<div class="items2">
<?
	foreach($arResult['ITEMS'] as $arItem){
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