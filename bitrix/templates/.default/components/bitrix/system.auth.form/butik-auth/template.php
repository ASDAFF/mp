<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="bx-system-auth-form">
<?if($arResult["FORM_TYPE"] == "login"):?>

<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR']) {
	ShowMessage($arResult['ERROR_MESSAGE']);
	?>
	<script>
		// $('.open-auth').click();
		$('#auth-handler').dialog('open');
	</script>
	<?
} else {
	?>
	<script>
		// debugger;
		$(document).ready(function () {
			if ($('.buy-link').length) {
				$('.buy-link').click();
			}
		});
	</script>
	<?
}
?>

<form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
	<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<?endif?>
	<?foreach ($arResult["POST"] as $key => $value):?>
		<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
	<?endforeach?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="AUTH" />
				<p class="reg-field">
					<input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" size="17" placeholder="<?=GetMessage("AUTH_LOGIN")?>" />
				</p>
				<p class="reg-field">
					<input type="password" name="USER_PASSWORD" maxlength="50" size="17" placeholder="<?=GetMessage("AUTH_PASSWORD")?>"/>
					<?if($arResult["SECURE_AUTH"]):?>
						<span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
							<div class="bx-auth-secure-icon"></div>
						</span>
						<noscript>
						<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
							<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
						</span>
						</noscript>
						<script type="text/javascript">
						document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
						</script>
					<?endif?>
				</p>
		<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
			<!-- <p class="reg-field">
				<input style="width: 20px;" type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" /><label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
			</p> -->
		<?endif?>
				<p class="reg-submit">
					<input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" />
				</p>

		<?if($arResult["AUTH_SERVICES"]):?>
					<div class="bx-auth-lbl"><?=GetMessage("socserv_as_user_form")?></div>
					<?
					$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons", 
						array(
							"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
							"SUFFIX"=>"form",
						), 
						$component, 
						array("HIDE_ICONS"=>"Y")
					);
					?>
		<?endif?>
</form>

<?if($arResult["AUTH_SERVICES"]):?>
	<?
	$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "", 
		array(
			"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
			"AUTH_URL"=>$arResult["AUTH_URL"],
			"POST"=>$arResult["POST"],
			"POPUP"=>"Y",
			"SUFFIX"=>"form",
		), 
		$component, 
		array("HIDE_ICONS"=>"Y")
	);
	?>
<?endif?>

<?
//if($arResult["FORM_TYPE"] == "login")
else:
?>

<form action="<?=$arResult["AUTH_URL"]?>">
	<table width="95%">
		<tr>
			<td align="center">
				<?=$arResult["USER_NAME"]?><br />
				[<?=$arResult["USER_LOGIN"]?>]<br />
				<a href="<?=$arResult["PROFILE_URL"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><?=GetMessage("AUTH_PROFILE")?></a><br />
			</td>
		</tr>
		<tr>
			<td align="center">
			<?foreach ($arResult["GET"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>
			<input type="hidden" name="logout" value="yes" />
			<input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" />
			</td>
		</tr>
	</table>
</form>
<?endif?>
</div>