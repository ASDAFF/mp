<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="last_comments_block">
	<p class="last_comments_block_title"><?=$arResult['TITLE']?></p><img class="comments_icon" src="<?=$this->__folder?>/images/comments_icon.png" alt="<?=$arResult['TITLE']?>" />
    <br class="clear" />
    <div class="comments_list">
    	<? 
		$count = count($arResult['ITEMS']);
		for($i = 0; $i < $count; $i++)
		{
		?>
    	<div class="comment_item">
        	<p class="comment_item_date"><?=$arResult['ITEMS'][$i]['DATE']?></p>
            <p>
            	<? if($arResult['ITEMS'][$i]['IS_REGISTERED'] == 'Y'):?>
            	<a href="<?=$arResult['ITEMS'][$i]['PROFILE_URL']?>" title="<?=$arResult['ITEMS'][$i]['USER_NAME']?>"><img src="<?=$this->__folder?>/images/userpic.png" alt="<?=$arResult['ITEMS'][$i]['USER_NAME']?>" /></a>
                <a href="<?=$arResult['ITEMS'][$i]['PROFILE_URL']?>" title="<?=$arResult['ITEMS'][$i]['USER_NAME']?>"><?=$arResult['ITEMS'][$i]['USER_NAME']?></a>
                <? else: ?>
                <img src="<?=$this->__folder?>/images/userpic.png" alt="<?=$arResult['ITEMS'][$i]['USER_NAME']?>" /> <?=$arResult['ITEMS'][$i]['USER_NAME']?>
                <? endif; ?>
                <?=GetMessage('LEAVE_COMMENT')?>
                <a href="<?=$arResult['ITEMS'][$i]['DETAIL_PAGE_URL']?>" title="<?=$arResult['ITEMS'][$i]['ELEMENT_NAME']?>"><?=$arResult['ITEMS'][$i]['ELEMENT_NAME']?></a>
            </p>
            <? if($arResult['SHOW_TEXT'] == 'Y'):?>
            <p class="text"><?=$arResult['ITEMS'][$i]['TEXT']?></p>
            <? endif;?>
        </div>
        <? if($i != $count - 1):?>
        <div class="divider"></div>
        <? endif; ?>
    	<?
		}
		?>
    </div>
</div>