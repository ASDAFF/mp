<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="popular_comments_block">
	<p class="popular_comments_block_title"><?=$arResult['TITLE']?></p><img class="comments_icon" src="<?=$this->__folder?>/images/heart.png" alt="<?=$arResult['TITLE']?>" />
    <br class="clear" />
    <div class="comments_list">
    	<? 
		$count = count($arResult['ITEMS']);
		for($i = 0; $i < $count; $i++)
		{
		?>
    	<div class="comment_item">
        	<? if($arResult['ITEMS'][$i]['IMAGE']): ?>
            <img src="<?=$arResult['ITEMS'][$i]['IMAGE']?>" alt="<?=$arResult['ITEMS'][$i]['ELEMENT_NAME']?>" class="preview" />
            <? endif; ?>
            
            
            <p>
                <a href="<?=$arResult['ITEMS'][$i]['DETAIL_PAGE_URL']?>" title="<?=$arResult['ITEMS'][$i]['ELEMENT_NAME']?>"><?=$arResult['ITEMS'][$i]['ELEMENT_NAME']?></a>
            </p>
            <p>
            	<?=GetMessage('PRMEDIA_COMMENTS')?>: <?=$arResult['ITEMS'][$i]['COMMENTS_COUNT']?>
            </p>
        </div>
        <? if($i != $count - 1):?>
        <div class="divider"></div>
        <? endif; ?>
    	<?
		}
		?>
    </div>
</div>