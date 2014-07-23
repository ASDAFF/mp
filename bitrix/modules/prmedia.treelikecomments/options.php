<?
global $USER, $APPLICATION;

// @todo show errors
if (!$USER->IsAdmin())
{
	return;
}
if (!CModule::IncludeModule("iblock"))
{
	return;
}

IncludeModuleLangFile(__FILE__);

$MODULE_ID = "prmedia.treelikecomments";

$arTabs = array(
	array(
		"DIV" => "edit1",
		"TAB" => GetMessage("PRMEDIA_TC_OPTION_MAIN"),
		"TITLE" => GetMessage("MAIN_TAB_TITLE_SET")
	)
);
$tabControl = new CAdminTabControl("tabControl", $arTabs);


// save settings
if ($_POST['SAVE'] && check_bitrix_sessid())
{
	COption::SetOptionString($MODULE_ID, 'admin_email', $_POST["admin_email"]);
	COption::SetOptionString($MODULE_ID, 'mail_sender', $_POST["mail_sender"]);

	COption::SetOptionString($MODULE_ID, 'moderators', implode(',', $_POST['moderators']));
	COption::SetOptionString($MODULE_ID, 'restricted_property', $_POST["restricted_property"]);

	COption::SetOptionString($MODULE_ID, 'stop_words', $_POST["stop_words"]);

	COption::SetOptionString($MODULE_ID, 'bb_code_enable', $_POST["bb_code_enable"]);
	COption::SetOptionString($MODULE_ID, 'smiles_enable', $_POST["smiles_enable"]);

	COption::SetOptionString($MODULE_ID, 'ban', $_POST['ban']);

	LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode($MODULE_ID) . "&lang=" . urlencode(LANGUAGE_ID));
}

$arGroups = explode(',', COption::GetOptionString($MODULE_ID, 'moderators'));
$rsGroup = CGroup::GetList(($by = "id"), ($order = "asc"), array());
$arModGroups = array();
while ($arFields = $rsGroup->GetNext())
{
	$arModGroups[] = array(
		"ID" => $arFields['ID'],
		"REFERENCE" => $arFields['REFERENCE'],
		"SELECTED" => in_array($arFields['ID'], $arGroups) ? "selected" : "",
	);
}

$restGroup = COption::GetOptionString($MODULE_ID, 'restricted_property');
$rsIblock = CIBlock::GetList(array(), array());
$arPropsRes = array();

while ($arIblock = $rsIblock->GetNext())
{
	$resProp = CIBlock::GetProperties($arIblock["ID"], array(), array("PROPERTY_TYPE" => "L"));
	while ($arProp = $resProp->GetNext())
	{
		$arPropsRes[] = array(
			"CODE" => $arProp["CODE"],
			"NAME" => $arProp["NAME"],
			"SELECTED" => ($restGroup == $arProp["CODE"]) ? "selected" : ""
		);
	}
}
?>

<form method="post" enctype="multipart/form-data" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($MODULE_ID) ?>&amp;lang=<?= LANGUAGE_ID ?>">

	<?
	$tabControl->Begin();
	$tabControl->BeginNextTab();
	?>

	<!-- admin email -->
	<tr>
		<td><?= GetMessage("PRMEDIA_TC_ADMIN_EMAIL") ?></td>
		<td>
			<input type="text" name="admin_email" value="<?= COption::GetOptionString($MODULE_ID, "admin_email") ?>" maxlength="255" size="50" />
		</td>
	</tr>

	<!-- sandler email -->
	<tr>
		<td><?= GetMessage("PRMEDIA_TC_MAIL_SENDER") ?></td>
		<td>
			<input type="text" name="mail_sender" value="<?= COption::GetOptionString($MODULE_ID, "mail_sender") ?>" maxlength="255" size="50" />
		</td>
	</tr>

	<!-- moderators groups -->
	<tr>
		<td><?= GetMessage("PRMEDIA_TC_MODERATORS") ?></td>
		<td>
			<select name="moderators[]" multiple="multiple">
				<? foreach ($arModGroups as $one): ?>
					<option <?= $one["SELECTED"] ?> value="<?= $one["ID"] ?>"><?= $one["REFERENCE"] ?></option>
				<? endforeach; ?>
			</select>
		</td>
	</tr>

	<!-- restricred property selector -->
	<tr>
		<td><?= GetMessage("PRMEDIA_TC_RESTRICTED_PROPERTY") ?></td>
		<td>
			<select name="restricted_property">
				<option <?
				if ($restGroup == ""): echo "selected";
				endif;
				?> value=""><?= GetMessage('PRMEDIA_TC_NOT_SELECTED') ?></option>
					<? foreach ($arPropsRes as $one): ?>
					<option <?= $one["SELECTED"] ?> value="<?= $one["CODE"] ?>"><?= $one["NAME"] ?></option>
				<? endforeach; ?>
			</select>
		</td>
	</tr>

	<!-- stopwords -->
	<tr>
		<td><?= GetMessage("PRMEDIA_TC_STOP_WORDS") ?></td>
		<td>
			<textarea rows="5" cols="54" name="stop_words"><?= COption::GetOptionString($MODULE_ID, "stop_words") ?></textarea>
		</td>
	</tr>

	<!-- bbcodes -->
	<tr>
		<td><?= GetMessage("PRMEDIA_TC_BBCODE_ENABLE") ?></td>
		<td>
			<input type="checkbox" <?= (COption::GetOptionString($MODULE_ID, "bb_code_enable") == 1) ? "checked" : "" ?> name="bb_code_enable" value="1" />
		</td>
	</tr>

	<!-- smiles -->
	<tr>
		<td><?= GetMessage("PRMEDIA_TC_SMILES_ENABLE") ?></td>
		<td>
			<input type="checkbox" <?= (COption::GetOptionString($MODULE_ID, "smiles_enable") == 1) ? "checked" : "" ?> name="smiles_enable" value="1" />
		</td>
	</tr>

	<!-- list of banned id addresses -->
	<tr>
		<td><?= GetMessage("PRMEDIA_TC_BAN") ?></td>
		<td>
			<textarea rows="5" cols="54" name="ban"><?= COption::GetOptionString($MODULE_ID, "ban") ?></textarea>
		</td>
	</tr>

	<!-- controls -->
	<? $tabControl->Buttons(); ?>
	<input class="adm-btn-save" type="submit" name="SAVE" value="<?= GetMessage("TC_SAVE") ?>" title="<?= GetMessage("TC_SAVE") ?>">
	<?= bitrix_sessid_post(); ?>
	<? $tabControl->End(); ?>
</form>