<div class="prmedia_tc_def">
<div style="width:auto; font-weight: normal;">
<div class="comments_block">
	<p class="comments_block_title"><strong><?=GetMessage('BEST_COMMENTS')?></strong></p>
	<div class="comments_list">
	<? foreach($arResult['COMMENTS'] as $COMMENT): ?>
		<div class="comment_item">
			<div <? if($arResult['SHOW_USERPIC'] == "Y"): ?> class="user_icon" <? endif; ?>>
				<? if($arResult['SHOW_USERPIC'] == 'Y'):?>
				<? if($COMMENT['USER']['USERLINK'] != ''): ?><a href="<?= $COMMENT['USER']['USERLINK'] ?>"><? endif; ?>
				<? if($COMMENT['USER']['PERSONAL_PHOTO']): ?>
				<img width="48" height="48" src="<?= $COMMENT['USER']['PERSONAL_PHOTO'];?>" alt="<?=$COMMENT['USER']['LOGIN']?>" />
				<? else: ?><img width="48" height="48" src="<?=$this->__folder?>/images/nophoto.gif" alt="<?=$COMMENT['USER']['LOGIN']?>" /><? endif; ?>
				<? if($COMMENT['USER']['USERLINK'] != ''): ?></a><? endif; ?>
				<? endif; ?>
			</div>
			<div class="comment_item_container">
			<? if($COMMENT['USER']['LOGIN']): ?>
				<div class="comment_item_top" <? if($arResult['SHOW_USERPIC'] != "Y"): echo 'style="margin-left:0px;"'; endif; ?>><? if($COMMENT['USER']['USERLINK'] != ''): ?><a href="<?=$COMMENT['USER']['USERLINK'] ?>" alt="<?=$COMMENT['USER']['LOGIN']?>"><?= $COMMENT['USER']['LOGIN']?></a><? else : ?><strong><?=$COMMENT['USER']['LOGIN']?></strong><? endif; ?><? if($arResult['SHOW_DATE'] == 'Y'): ?>, <?=$COMMENT['DATE_CREATE'] ?><? endif; ?><? if($COMMENT["COMMENT_LINK"] != "N"):?><a href="#<?= $COMMENT["COMMENT_LINK"] ?>" class="link_to_comment authorized">#</a><? endif; ?></div>
			<div class="comment_item_controls">
					<? if($arResult['ALLOW_RATING'] == 'Y'): ?>
						<? if($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteUp(<?=$COMMENT['ID']?>)"><? endif; ?><img src="<?=$this->__folder?>/images/up.png" alt="<? if($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "+1"; ?><? endif; ?>" /><? if($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?><span class="green up_<?= $COMMENT['ID'] ?>"><?= $COMMENT['VoteUp']; ?></span>
						<? if($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteDown(<?=$COMMENT['ID']?>)"><? endif; ?><img src="<?=$this->__folder?>/images/down.png" alt="<? if($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "-1"; ?><? endif; ?>" /><? if($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?><span class="red down_<?= $COMMENT['ID'] ?>"><?= $COMMENT['VoteDown']; ?></span>
				<? endif; ?>
				</div>
				<br class="clear" />
			<? endif; ?>
			<? if(!$COMMENT['USER']['LOGIN']): ?>
			<div class="comment_item_top" <? if($arResult['SHOW_USERPIC'] != "Y"): echo "style='margin-left:0px;'"; endif; ?>><strong><?=$COMMENT['AUTHOR_NAME']?></strong><? if($arResult['SHOW_DATE'] == 'Y'): ?>, <?= $COMMENT['DATE_CREATE'] ?><? endif; ?><? if($COMMENT["COMMENT_LINK"] != "N"):?><a href="#<?= $COMMENT["COMMENT_LINK"] ?>" class="link_to_comment">#</a><? endif; ?></div>
				<div class="comment_item_controls">
						<? if($arResult['ALLOW_RATING'] == 'Y'): ?>
						<? if($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteUp(<?=$COMMENT['ID']?>)"><? endif; ?><img src="<?=$this->__folder?>/images/up.png" alt="<? if($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "+1"; ?><? endif; ?>" /><? if($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?><span class="green up_<?= $COMMENT['ID'] ?>"><?= $COMMENT['VoteUp']; ?></span>
						<? if($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteDown(<?=$COMMENT['ID']?>)"><? endif; ?><img src="<?=$this->__folder?>/images/down.png" alt="<? if($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "-1"; ?><? endif; ?>" /><? if($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?><span class="red down_<?= $COMMENT['ID'] ?>"><?= $COMMENT['VoteDown']; ?></span>
					<? endif; ?>
				</div>
				<br class="clear" />
			<? endif; ?>
			<div class="comment_item_content"><?=$COMMENT['COMMENT']?></div>
			<br class="clear" />
		</div>
		<br class="clear" />
		</div>
	<? endforeach; ?>
	</div>
</div>
</div>
</div>