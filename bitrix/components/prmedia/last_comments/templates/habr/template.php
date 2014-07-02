<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="last_comments_light_block">
	<p class="last_comments_light_block_title"><?=$arResult['TITLE']?> <img src="<?=$this->__folder?>/images/down_arrow.png" alt="<?=$arResult['TITLE']?>" /></p>
    <div class="comments_list">
	   	<? foreach($arResult['ITEMS'] as $arItem): ?>
    	<div class="comment_item">
            <p>
            	<? if($arItem['IS_REGISTERED'] == 'Y'):?>
            	<a href="<?=$arItem['PROFILE_URL']?>" title="<?=$arItem['USER_NAME']?>"><img src="<?=$this->__folder?>/images/userpic.png" alt="<?=$arItem['USER_NAME']?>" /></a>
                <a href="<?=$arItem['PROFILE_URL']?>" title="<?=$arItem['USER_NAME']?>" class="comment_item_author"><?=$arItem['USER_NAME']?></a>
                <? else: ?>
				<img src="<?=$this->__folder?>/images/userpic.png" alt="<?=$arItem['USER_NAME']?>" /> <?=$arItem['USER_NAME']?>             
                <? endif;?>
                <img src="<?=$this->__folder?>/images/arrow.png" alt="" />
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?=$arItem['ELEMENT_NAME']?>"><?=$arItem['ELEMENT_NAME']?></a>
            </p>
            <? if($arResult['SHOW_TEXT'] == 'Y'):?>
            <p class="text"><?=$arItem['TEXT']?></p>
            <? endif;?>
        </div>
    	<? endforeach; ?>
    </div>
</div>