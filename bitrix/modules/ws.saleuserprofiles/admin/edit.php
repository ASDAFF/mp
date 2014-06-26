<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ws.saleuserprofiles/include.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sale/include.php");

IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("subscribe");
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("ws.saleuserprofiles_tabname"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("ws.saleuserprofiles_tabname")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$ID = intval($ID);		// Id of the edited record
$message = null;
$bVarsFromForm = false;
if($REQUEST_METHOD == "POST" && ($save!="" || $apply!="") && $POST_RIGHT=="W" && check_bitrix_sessid()){

    if (!$ID) {
        $res = WS_SaleUserProfilesManager::Add($_REQUEST["FIELDS"]);
        if (is_numeric($res)) {
            $ID = $res;
        }
    }

    if ($ID) {
        $res = WS_SaleUserProfilesManager::Update($ID, $_REQUEST["FIELDS"]);
    }

	if(!$err = $res->getErrorsAsString()){
		if($apply!="")
			LocalRedirect("/bitrix/admin/ws.saleuserprofiles_edit.php?ID=".$ID."&mess=ok&lang=".LANG."&".$tabControl->ActiveTabParam());
		else
			LocalRedirect("/bitrix/admin/ws.saleuserprofiles_list.php?lang=".LANG);
	}
	else{
		if($e = $APPLICATION->GetException()){
			$message = new CAdminMessage($err, $e);
        } else {
            $message = new CAdminMessage($err);
        }
		$bVarsFromForm = true;
	}

}

//Edit/Add part
ClearVars();
foreach (WS_SaleUserProfilesManager::GetProfileFieldsByID($ID) as $name => $value) {
    $GLOBALS["f_".$name] = $value;
}
foreach ($_REQUEST["FIELDS"] as $name => $value) {
    $GLOBALS["f_".$name] = htmlspecialchars($value);
}
$GLOBALS["f_PROPS"] = WS_SaleUserProfilesManager::GetProfileProps($f_ID, $f_PERSON_TYPE_ID);
foreach ($_REQUEST["FIELDS"]["PROPS"] as $propID=>$value) {
    $GLOBALS["f_PROPS"][$propID]["VALUE"] = htmlspecialchars($value);
}


$APPLICATION->SetTitle(GetMessage("ws.saleuserprofiles_tabname"). ': ' .$profileFields['NAME']);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

if($_REQUEST["mess"] == "ok" && $ID>0)
	CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("ws.saleuserprofiles_saved"), "TYPE"=>"OK"));

if($message)
	echo $message->Show();
elseif($rubric->LAST_ERROR!="")
	CAdminMessage::ShowMessage($rubric->LAST_ERROR);
?>

<form method="POST" Action="<?echo $APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="post_form">
<?
$tabControl->Begin();
?>
<?
$tabControl->BeginNextTab();
?>
    <tr>
        <td style="width:40%"><?=GetMessage("ws.saleuserprofiles_field_id")?></td>
        <td><?=$f_ID?></td>
    </tr>
    <tr>
        <td><?=GetMessage("ws.saleuserprofiles_field_date_update")?></td>
        <td><?=$f_DATE_UPDATE?></td>
    </tr>
    <tr>
        <td><?=GetMessage("ws.saleuserprofiles_field_person_type")?></td>
        <td>
            <?=WS_SaleUserProfilesManager::SelectBoxPersonTypes($f_PERSON_TYPE_ID, "FIELDS[PERSON_TYPE_ID]", 'onchange="this.form.submit()"');?>
        </td>
    </tr>
    <tr>
        <td><?=GetMessage("ws.saleuserprofiles_field_name")?></td>
        <td>
            <input type="text" name="FIELDS[NAME]" value="<?=$f_NAME?>" />
        </td>
    </tr>
    <tr>
        <td><?=GetMessage("ws.saleuserprofiles_field_user_id")?><span style="color:red">*</span></td>
        <td>
            <input type="text" id="post_form_user_id" name="FIELDS[USER_ID]" value="<?=$f_USER_ID?>"/>
            <input class="tablebodybutton" type="button" name="FindUser" id="FindUser" onclick="window.open('/bitrix/admin/user_search.php?lang=ru&amp;FN=post_form&amp;FC=post_form_user_id', '', 'scrollbars=yes,resizable=yes,width=760,height=500,top='+Math.floor((screen.height - 560)/2-14)+',left='+Math.floor((screen.width - 760)/2-5));" value="...">
        </td>
    </tr>
    <?foreach($f_PROPS as &$arProp):?>
	<tr>
		<td><?=$arProp["NAME"]?><?if($arProp["REQUIED"] === "Y"):?><span style="color:red">*</span><?endif?></td>
        <td>
            <?
                switch ($arProp["TYPE"]) {
                    case "CHECKBOX":
                        ?><input type="checkbox" name="FIELDS[PROPS][<?=$arProp["ID"]?>]" value="Y" <?if($arProp["VALUE"] === "Y"):?>checked="checked"<?endif?> /><?
                        break;
                    case "TEXT":
                        ?><input type="text" name="FIELDS[PROPS][<?=$arProp["ID"]?>]" value="<?=$arProp["VALUE"]?>" /><?
                        break;
                    case "SELECT":
                        ?>
                        <select name="FIELDS[PROPS][<?=$arProp["ID"]?>]">
                            <?foreach($arProp["variants"] as $variant):?>
                                <option value="<?=$variant['VALUE']?>" <?=(($variant['VALUE'] === $arProp["VALUE"]) ? " selected ": "")?>><?=$variant["NAME"]?></option>
                            <?endforeach?>
                        </select>
                        <?
                        break;
                    case "MULTISELECT":
                        $curVal = explode(",", $arProp["VALUE"]);
                        ?>
                        <select name="FIELDS[PROPS][<?=$arProp["ID"]?>][]" multiple="multiple">
                            <?foreach($arProp["variants"] as $variant):?>
                                <option value="<?=$variant['VALUE']?>" <?=((in_array($variant['VALUE'], $curVal)) ? " selected ": "")?>><?=$variant["NAME"]?></option>
                            <?endforeach?>
                        </select>
                        <?
                        break;
                    case "TEXTAREA":
                        ?>
                        <textarea name="FIELDS[PROPS][<?=$arProp["ID"]?>]"><?=$arProp["VALUE"]?></textarea>
                        <?
                        break;
                    case "LOCATION":
                        echo WS_SaleUserProfilesManager::SelectBoxLocations(LANGUAGE_ID, "FIELDS[PROPS][".$arProp["ID"]."]", $arProp['VALUE']);
                        break;
                    case "RADIO":
                        foreach($arProp["variants"] as $variant){
                            ?><input type="radio" name="FIELDS[PROPS][<?=$arProp["ID"]?>]" value="<?=$variant['VALUE']?>" <?=(($variant['VALUE'] === $arProp["VALUE"])?" checked":"")?>><?=htmlspecialchars($variant["NAME"])?><?
                        }
                        break;
                    default:
                        break;
                }
            ?>
        </td>
		<?/*<td width="60%"><input type="checkbox" name="ACTIVE" value="Y"<?if($str_ACTIVE == "Y") echo " checked"?>></td>*/?>
	</tr>
    <?endforeach?>
<?
$tabControl->Buttons(
	array(
		"disabled"=>($POST_RIGHT<"W"),
		"back_url"=>"ws.saleuserprofiles_list.php?lang=".LANG,

	)
);
?>
<?echo bitrix_sessid_post();?>
<input type="hidden" name="lang" value="<?=LANG?>">
<?if($ID>0 && !$bCopy):?>
	<input type="hidden" name="ID" value="<?=$ID?>">
<?endif;?>
<?
$tabControl->End();
?>

<?
$tabControl->ShowWarnings("post_form", $message);
?>

<?echo BeginNote();?>
<span class="required">*</span><?echo GetMessage("REQUIRED_FIELDS")?>
<?echo EndNote();?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>