<?

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/prmedia.treelikecomments/include.php");
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage("PRMEDIA_TC_COMMENT_EDIT_TITLE"));
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");
CJSCore::Init(array("jquery"));

$ID = $_GET['COMMENT_ID'];

$comment = new CTreelikeComments;

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("PRMEDIA_TC_COMMENT_EDIT"), "ICON" => "ib_settings", "TITLE" => GetMessage("PRMEDIA_TC_COMMENT")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($REQUEST_METHOD == "POST")
{
	if(trim($_POST["AUTHOR_NAME"]) == "")
		$_POST["AUTHOR_NAME"] = NULL;
		
	$arUpdate = array(
		"OBJECT_ID" => intval($_POST["OBJECT_ID"]),
		"AUTHOR_NAME" => $_POST["AUTHOR_NAME"],
		"COMMENT" => trim($_POST["COMMENT"]),
		"ACTIVATED" => intval($_POST["ACTIVATED"]),
	);
            
	$comment->Update($_POST['currentID'], $arUpdate);
	
	if(strlen($Update) > 0)
		LocalRedirect("/bitrix/admin/tc_comment_list.php");
	else
		LocalRedirect($APPLICATION->GetCurPage()."?COMMENT_ID=".$_POST['currentID']);
}

// If there is no comment

if(!$ID)
	return;
	
?>

<script>
$().ready(function() 
{
	$('form[name=form1]').submit(function() {
		
		if($("input[name=AUTHOR_NAME]").val() == ""){
			alert("<?= GetMessage("PRMEDIA_TC_AN_EMPTY") ?>");
			return false;
		}
		else if($("textarea[name=COMMENT]").val() == ""){
			alert("<?= GetMessage("PRMEDIA_TC_COMMENT_EMPTY") ?>");
			return false;			
		}
		else if(!CheckOI()){
			alert("<?= GetMessage("PRMEDIA_TC_OBJECT_ID_NUM") ?>");
			return false;			
		}
		else{
			return true;
		}
	});
	
	function CheckOI()
	{
	  var sval = $("input[name=OBJECT_ID]").val();
	 
	  if (sval == '')
	      return false;
	 
	  ival = parseInt(sval);
	  
	  if (isNaN(ival))
	      return false;
	 
	  if (ival != sval)
	      return false;
	 
	  if (ival <= 0)
	      return false;
	      
	  return true;
	 
	}
});
</script>

<h1><?= GetMessage("PRMEDIA_TC_COMMENT_EDIT") ?></h1>
<?$tabControl->Begin();
?>
<form name="form1" method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?echo LANGUAGE_ID?>">

<?$tabControl->BeginNextTab();?>
	<?
		$ps = $comment->GetByID(intval($ID));
		$res = $ps->fetch();
	?>
	<tr>
		<td width="20%">
			<?= GetMessage("PRMEDIA_TC_OBJECT_ID") ?>
		</td>
		<td width="30%">
			<input type="text" size="7" maxlength="50" value="<?= $res["OBJECT_ID"] ?>" name="OBJECT_ID" />
		</td>
	</tr>
	<? if($res["AUTHOR_NAME"]): ?>
	<tr>
		<td width="20%">
			<?= GetMessage("PRMEDIA_TC_AUTHOR_NAME") ?>
		</td>
		<td width="30%">
			<input type="text" maxlength="255" value="<?= $res["AUTHOR_NAME"] ?>" name="AUTHOR_NAME" />
		</td>
	</tr>
	<? endif; ?>
	<tr>
		<td width="20%" valign="top">
			<?= GetMessage("PRMEDIA_TC_COMMENT_TEXT") ?>
		</td>
		<td width="30%">
			<textarea rows="10" cols="70" name="COMMENT"><?= $res["COMMENT"] ?></textarea>
		</td>
	</tr>
	<tr>
		<td width="20%">
			<?= GetMessage("PRMEDIA_TC_ACTIVATED") ?>
		</td>
		<td width="30%">
			<input type="checkbox" <? if($res["ACTIVATED"] == 1): echo "checked"; endif; ?> value="1" name="ACTIVATED" />
		</td>
	</tr>

	<input type="hidden" name="currentID" value="<?= $ID ?>">
<?$tabControl->Buttons();?>

	<input class="adm-btn-save" type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>">
	<input type="submit" name="Apply" value="<?=GetMessage("MAIN_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
	
	<?=bitrix_sessid_post();?>

<?$tabControl->End();?>

</form>
<?

require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
?>
