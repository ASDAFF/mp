<!-- <div class="prmedia_tc_def">
	<p class="messages"><? ShowNote(implode("<br />", $arResult["MESSAGES"])); ?></p> -->
	<? if (!$arResult['ERRORS']['CAPTCHA']): ?>
		<? ShowError(implode("<br />", $arResult["ERRORS"])); ?>
	<? endif; ?>

	<? if ($arResult['A_NAME'] != ''): ?>
		<? $arResult['saveName'] = $arResult['A_NAME']; ?>
	<? endif; ?>

	<script type="text/javascript">
		<? if ($arResult['SCROLL_TO_COMMENT']): ?>
			var scroll = <?= $arResult['SCROLL_TO_COMMENT'] ?>;
		<? else: ?>
			var scroll = 0;
		<? endif; ?>
		var save_id = <?= $arResult['P_ID'] ?>;
		var generatedString = <?= '"' . $arResult["CAPTCHA_ROBOT_CODE"] . '"'; ?>;
		<? if ($arResult['TO_MOD'] == 1): ?>
			var to_moderate = 1;
		<? else: ?>
			var to_moderate = 0;
		<? endif; ?>
		var PLEASE_ENTER_COMMENT = <?= '"' . GetMessage('PLEASE_ENTER_COMMENT') . '"'; ?>;
		var PLEASE_ENTER_NAME = <?= '"' . GetMessage('PLEASE_ENTER_NAME') . '"'; ?>;
		var INVALID_EMAIL = <?= '"' . GetMessage('INVALID_EMAIL') . '"'; ?>;
		var CONFIRMATION_MULTI = <?= '"' . GetMessage('CONFIRMATION_MULTI') . '"'; ?>;
		var CONFIRMATION_SINGLE = <?= '"' . GetMessage('CONFIRMATION_SINGLE') . '"'; ?>;
		var DEL_SUCCESS_MULTI = <?= '"' . GetMessage('DEL_SUCCESS_MULTI') . '"'; ?>;
		var DEL_SUCCESS_SINGLE = <?= '"' . GetMessage('DEL_SUCCESS_SINGLE') . '"'; ?>;
		var ROBOT_ERROR = <?= '"' . GetMessage('ROBOT_ERROR') . '"'; ?>;
		var TYPE_LINK = <?= '"' . GetMessage('TYPE_LINK') . '"'; ?>;
		var TYPE_IMAGE = <?= '"' . GetMessage('TYPE_IMAGE') . '"'; ?>;
		var TYPE_LINK_TEXT = <?= '"' . GetMessage('TYPE_LINK_TEXT') . '"'; ?>;
	</script>
	<form <? if ($arResult['CURRENT_USER'] != 0): ?>onsubmit="document.getElementById('submitButton').disabled = true;
				document.getElementById('submitButton').style.cursor = 'wait';" <? endif; ?> method="post" action="" id="new_comment_form" style="display: block;" >
		<input type="hidden" name="PARENT_ID" value="0" />
		<input type="hidden" name="update_comment_id" value="0" />
		<input type="text" name="COMMENT" id="TEXT" placeholder="Ваш комментарий..." value="<?=($arResult['TEXT'] ? $arResult['TEXT'] : '')?>">

		<input type="hidden" name="comment_begin_time" value="<?= time(); ?>" />
		
		<button id="submitButton" type="submit" class="comment_submit" name="submit">
			<span class="add_comment_info"><?= GetMessage('ADD') ?></span>
			<!-- <span class="update_comment_info"><?= GetMessage('UPDATE') ?></span> -->
		</button>
	
	</form>
							
	<div class="comm-none">Оставьте свой отзыв ;)</div>
	

	<? foreach ($arResult['COMMENTS'] as $COMMENT): ?>
		<!-- <div class="blox-comm" style="margin-left: <?= $COMMENT['LEFT_MARGIN'] ?>px;"> -->
		<div class="blox-comm">
			<!-- <a href="#" class="like2">Популярные</a> <span class="pad-comm">|</span> <a href="#" class="new">Новое</a> -->
			<div class="user-comm"><?= $COMMENT['COMMENT'] ?></div>
			<div class="user-img-comm">
				<div class="cab-img3">
					<?if ($COMMENT['USER']['PERSONAL_PHOTO']) : ?>
						<img src="<?= $COMMENT['USER']['PERSONAL_PHOTO']; ?>" alt="" width="40" height="40" style="border-radius: 50%;">
					<?else : ?>
						<img src="<?= $this->__folder ?>/images/nophoto.gif" alt="" width="40" height="40" style="border-radius: 50%;">
					<?endif;?>
				</div>	
			</div>
			<div class="user-img-name"><?= $COMMENT['USER']['LOGIN'] ?> <span> <?= $COMMENT['DATE_CREATE'] ?></span></div>
			<div id="reply_to_<?= $COMMENT['ID'] ?>" class="answer" >
				<a id="comment_<?= $COMMENT['ID'] ?>" onclick="javascript:ReplyToComment(<?= $COMMENT['ID'] ?>);" href="javascript://" title="<?= GetMessage('REPLY') ?>" class="comment_item_reply_link"><?= GetMessage('REPLY') ?></a>
			</div>
		</div>
	<?endforeach;?>


<!-- 	<div class="blox-comm-sub">
											
		<div class="user-comm">C точки зрения банальной эрудиции каждый индивидуум, критически мотивирующий абстракцию, не может игнорировать критерии утопического субъективизма, концептуально интерпретируя общепринятые дефанизирующие поляризаторы, поэтому консенсус, достигнутый диалектической материальной классификацией всеобщих мотиваций в парадогматических связях предикатов, решает проблему усовершенствования формирующих геотрансплантационных квазипузлистатов всех кинетически коррелирующих аспектов.</div>
		
		<div class="user-img-comm">
			<div class="cab-img3"><img src="images/tovar_59.jpg" alt=""></div>	
		</div>
		
		<div class="user-img-name">katiaivanova <span>11.06.2014</span></div>
		<div class="answer"><a href="#">Ответ</a></div>
	
	</div>	 -->


	<!-- <div style="width:auto; font-weight: normal;">
		<? $arResult['HAS_SUBSIDIARIES'] = 0; ?>
		<? if ($arResult['NO_INDEX'] == 'Y'): ?>
			<noindex>
		<? endif; ?>
			<div class="comments_block">
				<? if ($arResult['SHOW_COUNT'] == 'Y'): ?>
					<p class="comments_block_title"><strong><?= GetMessage('COMMENTS_COUNT') ?> (<?= $arResult['COMMENTS_COUNT'] ?>)</strong></p>
				<? endif; ?>
				<div class="comments_list">
					<? foreach ($arResult['COMMENTS'] as $COMMENT): ?>
						<? if ((($COMMENT['ACTIVATED'] == 0 && $arResult['GROUPS']) || $arResult['ISADMIN']) || $COMMENT['ACTIVATED'] == 1): ?>
							<div class="comment_item <?
							if ($COMMENT['ACTIVATED'] == 0 && ($arResult['GROUPS'] || $arResult['ISADMIN'])): echo "not_approved";
							endif;
							?>" style="margin-left: <?= $COMMENT['LEFT_MARGIN'] ?>px;" id="comment_<?= $COMMENT['ID'] ?>">
								<div <? if ($arResult['SHOW_USERPIC'] == "Y"): ?> class="user_icon" <? endif; ?>>
									<? if ($arResult['SHOW_USERPIC'] == 'Y'): ?>
										<? if ($COMMENT['USER']['USERLINK'] != ''): ?><a href="<?= $COMMENT['USER']['USERLINK'] ?>"><? endif; ?>
											<? if ($COMMENT['USER']['PERSONAL_PHOTO']): ?>
												<img width="48" height="48" src="<?= $COMMENT['USER']['PERSONAL_PHOTO']; ?>" alt="<?= $COMMENT['USER']['LOGIN'] ?>" />
											<? else: ?><img width="48" height="48" src="<?= $this->__folder ?>/images/nophoto.gif" alt="<?= $COMMENT['USER']['LOGIN'] ?>" /><? endif; ?>
											<? if ($COMMENT['USER']['USERLINK'] != ''): ?></a><? endif; ?>
									<? endif; ?>
								</div>
								<div class="comment_item_container">
									<? foreach ($arResult['COMMENTS'] as $one): ?>
										<? if ($one['PARENT_ID'] == $COMMENT['ID']) $arResult['HAS_SUBSIDIARIES'] = 1; ?>
									<? endforeach; ?>
									<? if ($COMMENT['USER']['LOGIN']): ?>
										<div class="comment_item_top" <?
										if ($arResult['SHOW_USERPIC'] != "Y"): echo 'style="margin-left:0px;"';
										endif;
										?>><? if ($COMMENT['USER']['USERLINK'] != ''): ?><a href="<?= $COMMENT['USER']['USERLINK'] ?>" alt="<?= $COMMENT['USER']['LOGIN'] ?>"><?= $COMMENT['USER']['LOGIN'] ?></a><? else : ?><strong><?= $COMMENT['USER']['LOGIN'] ?></strong><? endif; ?><? if ($arResult['SHOW_DATE'] == 'Y'): ?>, <?= $COMMENT['DATE_CREATE'] ?><? endif; ?><? if ($COMMENT["COMMENT_LINK"] != "N"): ?><a href="#<?= $COMMENT["COMMENT_LINK"] ?>" class="link_to_comment authorized">#</a><? endif; ?></div>
										<div class="comment_item_controls">
											<? if ($arResult['ALLOW_RATING'] == 'Y'): ?>
												<? if ($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteUp(<?= $COMMENT['ID'] ?>)"><? endif; ?><img src="<?= $this->__folder ?>/images/up.png" alt="<? if ($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "+1"; ?><? endif; ?>" /><? if ($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?><span class="green up_<?= $COMMENT['ID'] ?>"><?= $COMMENT['VoteUp']; ?></span>
												<? if ($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteDown(<?= $COMMENT['ID'] ?>)"><? endif; ?><img src="<?= $this->__folder ?>/images/down.png" alt="<? if ($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "-1"; ?><? endif; ?>" /><? if ($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?><span class="red down_<?= $COMMENT['ID'] ?>"><?= $COMMENT['VoteDown']; ?></span>
											<? endif; ?>
											<? if ($arResult['GROUPS'] || $arResult['ISADMIN']): ?>
												<span class="admin-control">
													<? if ($COMMENT['ACTIVATED'] != 1): ?> <a id="activate_<?= $COMMENT['ID'] ?>" onclick="javascript:Activate(<?= $COMMENT['ID'] ?>, <?= "'" . $arResult['SEND_TO_USER_AFTER_ACTIVATE'] . "'" ?>, <?= "'" . $arResult['SEND_TO_USER_AFTER_ANSWERING'] . "'" ?>, <?= "'" . $arResult['SEND_TO_USER_AFTER_MENTION_NAME'] . "'" ?>);" href="javascript://"><img src="<?= $this->__folder ?>/images/approve.png" alt="<?= GetMessage("CONFIRM") ?>" /></a><? endif; ?>
													<a onclick="javascript:DeleteComment(<?= $COMMENT['ID'] . ', ' . $arResult['HAS_SUBSIDIARIES'] ?>);" href="javascript://"><img src="<?= $this->__folder ?>/images/delete.png" alt="<?= GetMessage("DELETE") ?>" /></a>
												</span>
											<? endif; ?>
										</div>
										<br class="clear" />
									<? endif; ?>
									<? if (!$COMMENT['USER']['LOGIN']): ?>
										<div class="comment_item_top" <?
										if ($arResult['SHOW_USERPIC'] != "Y"): echo "style='margin-left:0px;'";
										endif;
										?>><strong><?= $COMMENT['AUTHOR_NAME'] ?></strong><? if ($arResult['SHOW_DATE'] == 'Y'): ?>, <?= $COMMENT['DATE_CREATE'] ?><? endif; ?><? if ($COMMENT["COMMENT_LINK"] != "N"): ?><a href="#<?= $COMMENT["COMMENT_LINK"] ?>" class="link_to_comment">#</a><? endif; ?></div>
										<div class="comment_item_controls">
											<? if ($arResult['ALLOW_RATING'] == 'Y'): ?>
												<? if ($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteUp(<?= $COMMENT['ID'] ?>)"><? endif; ?><img src="<?= $this->__folder ?>/images/up.png" alt="<? if ($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "+1"; ?><? endif; ?>" /><? if ($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?><span id="up_<?= $COMMENT['ID'] ?>" class="green"><?= $COMMENT['VoteUp']; ?></span>
												<? if ($arResult['CURRENT_USER'] != 0): ?><a href="javascript://" onclick="javascript:VoteDown(<?= $COMMENT['ID'] ?>)"><? endif; ?><img src="<?= $this->__folder ?>/images/down.png" alt="<? if ($arResult['CURRENT_USER'] == 0): ?><?= GetMessage("VOTE_AUTH_ERROR") ?><? else: echo "-1"; ?><? endif; ?>" /><? if ($arResult['CURRENT_USER'] != 0): ?></a><? endif; ?><span id="down_<?= $COMMENT['ID'] ?>" class="red"><?= $COMMENT['VoteDown']; ?></span>
											<? endif; ?>
											<? if ($arResult['GROUPS'] || $arResult['ISADMIN']): ?>
												<span class="admin-control">
													<? if ($COMMENT['ACTIVATED'] != 1): ?> <a id="activate_<?= $COMMENT['ID'] ?>" onclick="javascript:Activate(<?= $COMMENT['ID'] ?>, <?= "'" . $arResult['SEND_TO_USER_AFTER_ACTIVATE'] . "'" ?>, <?= "'" . $arResult['SEND_TO_USER_AFTER_ANSWERING'] . "'" ?>, <?= "'" . $arResult['SEND_TO_USER_AFTER_MENTION_NAME'] . "'" ?>);" href="javascript://"><img src="<?= $this->__folder ?>/images/approve.png" alt="<?= GetMessage("CONFIRM") ?>" /></a><? endif; ?>
													<a onclick="javascript:DeleteComment(<?= $COMMENT['ID'] . ', ' . $arResult['HAS_SUBSIDIARIES'] ?>);" href="javascript://"><img src="<?= $this->__folder ?>/images/delete.png" alt="<?= GetMessage("DELETE") ?>" /></a>
												</span>
											<? endif; ?>
										</div>
										<br class="clear" />
									<? endif; ?>
									<div class="comment_item_content">
										<?= $COMMENT['COMMENT'] ?>
										<? if (!empty($COMMENT['MODIFY_STRING'])): ?>
											<br /><span class="modify-info"><?= $COMMENT['MODIFY_STRING'] ?></span>
										<? endif; ?>
										<? if ($COMMENT['CAN_EDIT'] == "Y" && $arResult['COMMENT_NOT_ALLOWED'] !== true): ?>
											<br /><a class="modify-link" id="update_comment_<?= $COMMENT['ID'] ?>" href="javascript:void(0);" onclick='javascript:UpdateComment(<?= $COMMENT['ID'] ?>);'><?= GetMessage('EDIT_MSG') ?></a>
										<? endif; ?>
									</div>
									<div class="comment_hidden_content comment_hidden_content_<?= $COMMENT['ID'] ?>"><?= $COMMENT['COMMENT_HIDDEN_CONTENT'] ?></div>
									<? if ($arResult['CAN_COMMENT'] == 'Y' && $arResult['MAX_DEPTH_LEVEL'] > 1 && $COMMENT['ACTIVATED'] == 1 && $arResult['COMMENT_NOT_ALLOWED'] !== true): ?>
										<div id="reply_to_<?= $COMMENT['ID'] ?>">
											<a id="comment_<?= $COMMENT['ID'] ?>" onclick="javascript:ReplyToComment(<?= $COMMENT['ID'] ?>);" href="javascript://" title="<?= GetMessage('REPLY') ?>" class="comment_item_reply_link"><?= GetMessage('REPLY') ?></a>
										</div>
									<? endif; ?>
									<br class="clear" />
								</div>
								<br class="clear" />
							</div>
							<? $arResult['HAS_SUBSIDIARIES'] = 0; ?>
						<? endif; ?>
					<? endforeach; ?>
				</div>
				<br class="clear" />
				<? if ($arResult['CAN_COMMENT'] == 'Y' && $arResult['COMMENT_NOT_ALLOWED'] !== true): ?>
					<div class="comment_reply"><div id="reply_to_0"><a onclick="javascript:ReplyToComment(0);" href="javascript://" id="leave_comment_link"><?= GetMessage('LEAVE_COMMENT') ?></a></div></div>
				<? elseif ($arResult['COMMENT_NOT_ALLOWED'] !== true): ?>
					<a href="<?= $arResult['AUTH_PATH'] ?>"><?= GetMessage('AUTH') ?></a>
				<? else: ?>
					<?= ShowError(GetMessage("COMMENT_NOT_ALLOWED")); ?>
				<? endif; ?>
				<? if ($arResult['COMMENT_NOT_ALLOWED'] !== true): ?>
				<form <? if ($arResult['CURRENT_USER'] != 0): ?>onsubmit="document.getElementById('submitButton').disabled = true;
				document.getElementById('submitButton').style.cursor = 'wait';" <? endif; ?> method="post" action="" id="new_comment_form" style="display: none;" >
					<fieldset id="addform" style="border: none;">
						<?
						if ($arResult["ERRORS"]['CAPTCHA']): echo ShowError($arResult["ERRORS"]['CAPTCHA']);
						endif;
						?>
						<? if ($arResult['CURRENT_USER'] == 0): ?>
							<? if ($arResult['PREMODERATION'] == "Y" && !$arResult['ERRORS']['CAPTCHA']): ?><p id="form_show" align="left" style="color: green; margin-bottom: 10px; font-size: 12px; padding-top:15px;"><?= GetMessage('MODERATION'); ?></p><? endif; ?>
							<p><font color="red">*&nbsp;</font><?= GetMessage("NAME"); ?>:</p><input class="blured" id="AUTHOR_NAME" value="<?= $arResult['saveName'] ?>" type="text" name="AUTHOR_NAME" />
							<p><?= GetMessage("EMAIL"); ?>:</p><input id="EMAIL" value="<?= $arResult['EMAIL'] ?>" type="text" name="EMAIL" />
						<? endif; ?>
						<p><font color="red">*&nbsp;</font><span class="add_comment_info"><?= GetMessage("YOUR_COMMENT"); ?></span><span class="update_comment_info"><?= GetMessage("YOUR_COMMENT_UPDATE"); ?></span>:</p>
						<? if ($arResult['SHOW_FILEMAN'] == 1): ?>
							<div class="editor">
								<a title="<?= GetMessage("b"); ?>" tabindex="-1" onclick="javascript:encloseSelection('[b]', '[/b]');" href="javascript://"><img alt="<?= GetMessage("b"); ?>" src="<?= $this->__folder ?>/images/icons/b.jpg" /></a>
								<a title="<?= GetMessage("i"); ?>" tabindex="-1" onclick="javascript:encloseSelection('[i]', '[/i]');" href="javascript://"><img alt="<?= GetMessage("i"); ?>" src="<?= $this->__folder ?>/images/icons/i.jpg" /></a>
								<a title="<?= GetMessage("u"); ?>" tabindex="-1" onclick="javascript:encloseSelection('[u]', '[/u]');" href="javascript://"><img alt="<?= GetMessage("u"); ?>" src="<?= $this->__folder ?>/images/icons/u.jpg" /></a>
								<a title="<?= GetMessage("s"); ?>" tabindex="-1" onclick="javascript:encloseSelection('[s]', '[/s]');" href="javascript://"><img alt="<?= GetMessage("s"); ?>" src="<?= $this->__folder ?>/images/icons/s.jpg" /></a>
								<a id="quoteIcon" title="<?= GetMessage("q"); ?>" tabindex="-1" href="javascript://"><img alt="<?= GetMessage("q"); ?>" src="<?= $this->__folder ?>/images/icons/quote.jpg" /></a>
								<a title="<?= GetMessage("c"); ?>" tabindex="-1" onclick="javascript:encloseSelection('[code]', '[/code]');" href="javascript://"><img alt="<?= GetMessage("c"); ?>" src="<?= $this->__folder ?>/images/icons/code.jpg" /></a>
								<a title="<?= GetMessage("l"); ?>" tabindex="-1" id="link" href="javascript://"><img alt="<?= GetMessage("l"); ?>" src="<?= $this->__folder ?>/images/icons/link.jpg" /></a>
								<a title="<?= GetMessage("image"); ?>" tabindex="-1" id="image" href="javascript://"><img alt="<?= GetMessage("image"); ?>" src="<?= $this->__folder ?>/images/icons/image.jpg" /></a>
								<? if ($arResult['ALLOW_SMILES'] == 1): ?>
									<a href="javascript://" tabindex="-1" onclick="return insertAtCursor(':)')"><img src="<?= $this->__folder ?>/images/icons/smile.jpg" /></a>
									<a href="javascript://" tabindex="-1" onclick="return insertAtCursor(':D')"><img src="<?= $this->__folder ?>/images/icons/xd.jpg" /></a>
									<a href="javascript://" tabindex="-1" onclick="return insertAtCursor(':(')"><img src="<?= $this->__folder ?>/images/icons/sad.jpg" /></a>
									<a href="javascript://" tabindex="-1" onclick="return insertAtCursor('x_x')"><img src="<?= $this->__folder ?>/images/icons/x_x.jpg" /></a>
									<a href="javascript://" tabindex="-1" onclick="return insertAtCursor('0_0')"><img src="<?= $this->__folder ?>/images/icons/0_0.jpg" /></a>
								<? endif; ?>
							</div>
						<? endif; ?>
						<textarea name="COMMENT" id="TEXT"><?= $arResult['TEXT'] ?></textarea>
						<input type="hidden" name="PARENT_ID" value="0" />
						<input type="hidden" name="update_comment_id" value="0" /><br />
						<? if ($arResult["CAPTCHA_TYPE"] == "CAPTCHA_BITRIX"): ?>
							<p><font color="red">*&nbsp;</font><?= GetMessage("CAPTCHA"); ?>:</p>
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" alt="CAPTCHA" />
							<input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>" />
							<input type="text" name="captcha_word" class="comment_captcha" />
						<? elseif ($arResult["CAPTCHA_TYPE"] == "ROBOT"): ?>
							<div class="checkRobot"><input id="checkRobotInput" type="checkbox" value="Y" name="ROBOT" checked="checked"  /><label id="checkRobotLabel"><?= GetMessage("robot"); ?></label></div>
							<input type="hidden" id="robotString" name="ROBOT_STRING" value=""  />
							<input type="hidden" id="newRobotString" name="NEW_ROBOT_STRING" value="fail" />
						<? endif; ?>
						<input type="hidden" name="comment_begin_time" value="<?= time(); ?>" />
						<button id="submitButton" type="submit" class="comment_submit" name="submit"><span class="add_comment_info"><?= GetMessage('ADD') ?></span><span class="update_comment_info"><?= GetMessage('UPDATE') ?></span></button>
					</fieldset>
				</form>
				<? endif; ?>
			<? if ($arResult['NO_INDEX'] == 'Y'): ?></noindex><? endif; ?>
	</div>
</div>
</div> -->