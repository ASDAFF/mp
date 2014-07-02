<div class="prmedia_tc_habr">
<div style="width:auto; font-weight: normal;">
<div class="comments_block">
	<p class="comments_block_title"><strong><?=GetMessage('BEST_COMMENTS')?></strong></p>
	<div class="comments_list">
	<? foreach($arResult['COMMENTS'] as $COMMENT): ?>
		<div class="comment_item">
			<div <? if($arResult['SHOW_USERPIC'] == "Y"): ?> <? if(!$COMMENT['USER']['PERSONAL_PHOTO']): ?> class="user_icon_noavatar" <? endif; ?> class="user_icon" <? endif; ?>>
		<? if($COMMENT['USER']['LOGIN'] and $arResult['SHOW_USERPIC'] == 'Y' and $COMMENT['USER']['PERSONAL_PHOTO']):?>
		<? if($COMMENT['USER']['PERSONAL_PHOTO']):?><? if($COMMENT['USER']['USERLINK'] != ''): ?><a href="<?= $COMMENT['USER']['USERLINK'] ?>"><? endif; ?><img width="22" height="22" src="<?= $COMMENT['USER']['PERSONAL_PHOTO'];?>" alt="<?=$COMMENT['USER']['LOGIN']?>" /><? if($COMMENT['USER']['USERLINK'] != ''): ?></a><? endif; ?><? endif; ?>
				<? endif; ?>
			</div>
			<? if($COMMENT['USER']['LOGIN']): ?>
				<div class="comment_item_top" <? if($arResult['SHOW_USERPIC'] != "Y"): echo 'style="margin-left:0px;"'; endif; ?>><? if($COMMENT['USER']['USERLINK'] != ''): ?><a href="<?=$COMMENT['USER']['USERLINK'] ?>" alt="<?=$COMMENT['USER']['LOGIN']?>"><?=$COMMENT['USER']['LOGIN']?></a><? else : ?><strong><?=$COMMENT['USER']['LOGIN']?></strong><? endif; ?><? if($arResult['SHOW_DATE'] == 'Y'): ?>, <?=$COMMENT['DATE_CREATE'] ?><? endif; ?><? if($COMMENT["COMMENT_LINK"] != "N"):?><a href="#<?= $COMMENT["COMMENT_LINK"] ?>" class="link_to_comment authorized">#</a><? endif; ?></div>
			<div class="comment_item_controls">
					<? if($arResult['ALLOW_RATING'] == 'Y'): ?>
					<span <? if($COMMENT['TOTAL_VOTE'] * -1 < 0): ?> class="green up_<?=$COMMENT['ID']?>" <? else: ?> class="red up_<?=$COMMENT['ID']?>"<? endif; ?>><? if($COMMENT['TOTAL_VOTE'] != 0): ?><?=$COMMENT['TOTAL_VOTE']?><? endif; ?></span>
					<? if($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteUp(<?=$COMMENT['ID']?>)"><? endif; ?><img src="<?=$this->__folder?>/images/up.png" alt="<? if($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "+1"; ?><? endif; ?>" /><? if($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?>
					<? if($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteDown(<?=$COMMENT['ID']?>)"><? endif; ?><img src="<?=$this->__folder?>/images/down.png" alt="<? if($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "-1"; ?><? endif; ?>" /><? if($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?>
				<? endif; ?>
				</div>
			<? endif; ?>
			<? if(!$COMMENT['USER']['LOGIN']): ?>
				<div class="comment_item_top" <? if($arResult['SHOW_USERPIC'] != "Y"): echo "style='margin-left:0px;'"; endif; ?>><strong><?=$COMMENT['AUTHOR_NAME']?></strong><? if($arResult['SHOW_DATE'] == 'Y'): ?>, <?= $COMMENT['DATE_CREATE'] ?><? endif; ?><? if($COMMENT["COMMENT_LINK"] != "N"):?><a href="#<?= $COMMENT["COMMENT_LINK"] ?>" class="link_to_comment">#</a><? endif; ?></div>
					<div class="comment_item_controls">
						<? if($arResult['ALLOW_RATING'] == 'Y'): ?>
						<span id="up_<?=$COMMENT['ID']?>" <?if ($COMMENT['TOTAL_VOTE'] * -1 < 0): ?> class="green" <? else: ?> class="red" <? endif; ?>><?if ($COMMENT['TOTAL_VOTE'] != 0): ?><?= $COMMENT['TOTAL_VOTE'] ?><? endif; ?></span>
						<? if($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteUp(<?=$COMMENT['ID']?>)"><? endif; ?><img src="<?=$this->__folder?>/images/up.png" alt="<? if($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "+1"; ?><? endif; ?>" /><? if($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?>
						<? if($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteDown(<?=$COMMENT['ID']?>)"><? endif; ?><img src="<?=$this->__folder?>/images/down.png" alt="<? if($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "-1"; ?><? endif; ?>" /><? if($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?>
						<? endif; ?>
				</div>
				<? endif; ?>
			<br class="clear" />
			<div class="comment_item_content"><?=$COMMENT['COMMENT']?></div>
			<br class="clear" />
			<div class="divider"></div>
		</div>
	<? endforeach; ?>
	</div>
</div>
</div>
</div>