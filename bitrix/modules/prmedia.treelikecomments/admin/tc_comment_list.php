<?

global $APPLICATION, $DB, $USER, $CACHE_MANAGER;

// Setting Module ID

$MODULE_ID = "prmedia.treelikecomments";

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetAdditionalCSS('/bitrix/themes/.default/'.$MODULE_ID.'.css');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$MODULE_ID."/include.php");

IncludeModuleLangFile(__FILE__);

$sTableID = "prmedia_treelike_comments_list"; // ID таблицы

$oSort = new CAdminSorting($sTableID, "ID", "desc"); // объект сортировки
$lAdmin = new CAdminList($sTableID, $oSort); // основной объект списка

$OComments = new CTreelikeComments;

$arFilterRows = array(
		GetMessage("TC_AUTHOR_NAME"),
        GetMessage("TC_EMAIL"),
        GetMessage("TC_IP"),
		GetMessage("TC_COMMENT"),
        GetMessage("TC_OBJECT"),
        GetMessage("TC_DATE"),
        GetMessage("TC_SITEID"),
        GetMessage("TC_ACTIVATED"),
				GetMessage("TC_DATE_MODIFY")
	);

$filter = new CAdminFilter(
	$sTableID."_filter_id",
	$arFilterRows
);

$arFilterFields = Array(
    "find_id",
	"find_author_name",
    "find_email",
    "find_ip",
    "find_comment",
    "find_object_id",
    "find_date",
    "find_site_id",
    "find_activated",
		"find_date_modify"
	);


$lAdmin->InitFilter($arFilterFields);

if(($arID = $lAdmin->GroupAction()))
{
    if($_REQUEST['action_target']=='selected')
	{
		$rsData = $OComments->GetList(array($by=>$order), $arFilter, "", false, true);
		while($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}

  foreach($arID as $ID)
  {

    if(strlen($ID)<=0)
      continue;

      $ID = IntVal($ID);

      $objectID = $OComments->GetObjectData($ID);

      $OComments->Delete($ID, false);
      $CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_".$objectID);
  }

}

$lAdmin->AddHeaders(array(
  array(  "id"    =>"ID",
    "content"  =>"ID",
    "sort"    =>"id",
    "align"    =>"center",
    "default"  =>true,
  ),

  array(  "id"    =>"DATE",
    "content"  => GetMessage("TC_DATE"),
    "sort"    =>"DATE",
    "default"  =>true,
  ),

    array(  "id"    =>"AUTHOR_NAME",
    "content"  => GetMessage("TC_AUTHOR_NAME"),
    "sort"    =>"AUTHOR_NAME",
    "default"  =>true,
  ),

  array(  "id"    =>"EMAIL",
    "content"  => GetMessage("TC_EMAIL"),
    "sort"    =>"EMAIL",
    "default"  =>true,
  ),

  array(  "id"    =>"IP",
    "content"  => GetMessage("TC_IP"),
    "sort"    =>"IP",
    "default"  =>true,
  ),

    array(  "id"    =>"COMMENT",
    "content"  => GetMessage("TC_COMMENT"),
    "sort"    =>"COMMENT",
    "default"  =>true,
  ),

    array(  "id"    =>"OBJECT_ID",
    "content"  => GetMessage("TC_OBJECT_ID"),
    "sort"    =>"OBJECT_ID",
    "default"  =>true,
  ),

      array(  "id"    =>"SITE_ID",
    "content"  => GetMessage("TC_SITEID"),
    "sort"    =>"SITE_ID",
    "default"  =>true,
  ),

  	array(  "id"    =>"ACTIVATED",
    "content"  => GetMessage("TC_ACTIVATED"),
    "sort"    =>"ACTIVATED",
    "default"  =>true,
  ),
	array(  "id"    =>"DATE_MODIFY",
    "content"  => GetMessage("TC_DATE_MODIFY"),
    "sort"    =>"DATE_MODIFY",
    "default"  =>true,
  ),

));


if($find_activated == GetMessage("TC_YES"))
	$find_activated = 1;
else if($find_activated == GetMessage("TC_NO"))
	$find_activated = 0;

$arFilter = Array(
	"ID"		=> $find_id,
	"AUTHOR_NAME"	=> $find_author_name,
    "EMAIL"	=> $find_email,
    "IP"	=> $find_ip,
	"COMMENT"	=> $find_comment,
    "OBJECT_ID"	=> $find_object_id,
    "DATE"	=> $find_date,
    "SITE_ID"	=> $find_site_id,
    "ACTIVATED"	=> $find_activated,
		"DATE_MODIFY" => $find_date_modify
);

  $result = $OComments->GetList(Array($by=>$order), $arFilter, "", false, true);
  $result = new CAdminResult($result, $sTableID);
  $result->NavStart();
  $lAdmin->NavText($result->GetNavPrint(GetMEssage("TC_PAGES")));

CModule::IncludeModule("iblock");

while($myrow = $result->NavNext(true, "f_"))
{

	$iblock_element = '';

	$res = CIBlockElement::GetByID($myrow['OBJECT_ID']);
	if($ar_res = $res->GetNext())
	{
		$iblock_element_name = $ar_res['NAME'];
		$iblock_element_iblock_id = $ar_res['IBLOCK_ID'];

		$res_iblock_type = CIBlock::GetByID($ar_res['IBLOCK_ID']);
		if ($ar_res_iblock_type = $res_iblock_type->GetNext())
			$iblock_element_type = $ar_res_iblock_type['IBLOCK_TYPE_ID'];
	}

	if(isset($myrow['USER_ID']) && !isset($myrow['AUTHOR_NAME']))
	{
		$rsUser = CUser::GetByID($myrow['USER_ID']);
		$arUser = $rsUser->Fetch();
		$result_user = '<a href="user_edit.php?ID='.$myrow['USER_ID'].'&lang='.LANG.'">'.$arUser['LOGIN'].'</a>'; // Получаем логин пользователя
		if(!empty($myrow['NAME']) || !empty($myrow['LAST_NAME']))
			$result_user = $myrow['NAME'].' '.$myrow['LAST_NAME']. ' <a href="user_edit.php?ID='.$myrow['USER_ID'].'&lang='.LANG.'">['. $myrow['LOGIN'].']</a>';
	}
	elseif(!isset($myrow['USER_ID']) && isset($myrow['AUTHOR_NAME']))
	{
		$result_user = $myrow['AUTHOR_NAME'];
	}

	if($myrow['ACTIVATED'] == 1)
		$myrow['ACTIVATED'] = GetMessage("TC_YES");
	else
		$myrow['ACTIVATED'] = GetMessage("TC_NO");

	$author_mail = $OComments->AuthorMail($myrow['ID']);

	if($author_mail == "N")
		$author_mail = '';

	$row =& $lAdmin->AddRow($myrow['ID'], $myrow);
	$row->AddViewField("ID", $myrow['ID']);

	$row->AddViewField("DATE", $myrow['NEW_DATE']);
	$row->AddViewField("AUTHOR_NAME", $result_user);
	$row->AddViewField("EMAIL", $author_mail);
	$row->AddViewField("IP", $myrow["REMOTE_ADDR"]);
	$row->AddViewField("COMMENT", HTMLToTxt($myrow['COMMENT']));
	$row->AddViewField("OBJECT_ID", $iblock_element_name. '<a style="padding-left: 3px;" href="iblock_element_edit.php?WF=Y&ID='.$myrow['OBJECT_ID'].'&type='.$iblock_element_type.'&lang='.LANG.'&IBLOCK_ID='.$iblock_element_iblock_id.'&find_section_section=-1" />['.$myrow['OBJECT_ID'].']</a>');
	$row->AddViewField("SITE_ID", $myrow['SITE_ID']);
	$row->AddViewField("ACTIVATED", $myrow['ACTIVATED']);
	$row->AddViewField("DATE_MODIFY", $myrow['DATE_MODIFY']);

	$result_user = '';

	$remoteAddr = CTreelikeComments::GetIP();
	$arFilter = array(
		"REMOTE_ADDR" => $remoteAddr

	);
	$obComment = new CTreelikeComments;
	$commentsCount = $obComment->GetList(false, $arFilter, false, true);
	$banDeleteConfirm = str_replace("#COMMENTS_COUNT#", $commentsCount, GetMessage('TC_BAN_DELETE_CONFIRM'));
	$banDeleteConfirm = str_replace("#REMOTE_ADDR#", $remoteAddr, $banDeleteConfirm);
	$arWordEndings = explode(",", GetMessage('TC_BAN_DELETE_WORD_ENDS'));
	$banDeleteConfirm = str_replace("#WORD_END#", getNumericSuffix($arWordEndings, $commentsCount), $banDeleteConfirm);

	// row actions
	$arActions = Array();
	$arActions[] = array(
		"TEXT" => GetMessage("TC_ACTIVATE"),
		"ACTION" => $lAdmin->ActionRedirect("tc_comment_actions.php?action=activate&id=" . $myrow['ID'])
	);
	$arActions[] = array(
		"TEXT" => GetMessage("TC_DEACTIVATE"),
		"ACTION" => $lAdmin->ActionRedirect("tc_comment_actions.php?action=deactivate&id=" . $myrow['ID'])
	);
	$arActions[] = array(
		"TEXT" => GetMessage("TC_BAN"),
		"ACTION" => $lAdmin->ActionRedirect("tc_comment_actions.php?action=ban&id=" . $myrow['ID'])
	);
	$arActions[] = array(
		"TEXT" => GetMessage("TC_BAN_DELETE"),
		"ACTION" => "if(confirm('" . $banDeleteConfirm . "')) " . $lAdmin->ActionRedirect("tc_comment_actions.php?action=ban_delete&id=" . $myrow['ID'])
	);
	$arActions[] = array(
		"ICON" => "edit",
		"TEXT" => GetMessage("TC_EDIT"),
		"ACTION" => $lAdmin->ActionRedirect("tc_comment_edit.php?COMMENT_ID=" . $myrow['ID'])
	);
	$arActions[] = array(
		"ICON" => "delete",
		"TEXT" => GetMessage("TC_DELETE"),
		"ACTION" => "if(confirm('" . GetMessage("TC_CONFIRM") . "')) " . $lAdmin->ActionDoGroup($myrow['ID'], "delete")
	);
	$arActions[] = array("SEPARATOR" => true);
	if (is_set($arActions[count($arActions) - 1], "SEPARATOR"))
	{
		unset($arActions[count($arActions) - 1]);
	}
	$row->AddActions($arActions);

}


$lAdmin->AddFooter(
  array(
    array("title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=>$result->SelectedRowsCount()), // кол-во элементов
    array("counter"=>true, "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"), // счетчик выбранных элементов
  )
);


$lAdmin->AddGroupActionTable(Array(
  "delete"=>GetMessage("MAIN_ADMIN_LIST_DELETE"), // удалить выбранные элементы
  ));


$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("TC_TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$get_right_groups = new CTreelikeComments;

if(!$get_right_groups->IsModerator())
{
    echo CAdminMessage::ShowMessage(GetMessage("TC_ERROR_ACCESS"));
}
else
{
    ?>

    <form name="form2" method="GET" action="<?echo $APPLICATION->GetCurPage()?>?">
    <?$filter->Begin();?>
    <tr>
    	<td nowrap><?= GetMessage("TC_ID") ?>:</td>
    	<td nowrap><input type="text" name="find_id" value="<?echo htmlspecialchars($find_id)?>" size="10"></td>
    </tr>

    <tr>
    	<td nowrap><?= GetMessage("TC_AUTHOR_NAME") ?>:</td>
    	<td nowrap><input type="text" name="find_author_name" value="<?echo htmlspecialchars($find_author_name)?>" size="44"></td>
    </tr>

    <tr>
    	<td nowrap><?= GetMessage("TC_EMAIL") ?>:</td>
    	<td nowrap><input type="text" name="find_email" value="<?echo htmlspecialchars($find_email)?>" size="44"></td>
    </tr>

    <tr>
    	<td nowrap><?= GetMessage("TC_IP") ?>:</td>
    	<td nowrap><input type="text" name="find_ip" value="<?echo htmlspecialchars($find_ip)?>" size="44"></td>
    </tr>

    <tr>
    	<td nowrap><?= GetMessage("TC_COMMENT") ?>:</td>
    	<td nowrap><input type="text" name="find_comment" value="<?echo htmlspecialchars($find_comment)?>" size="44"></td>
    </tr>

    <tr>
    	<td nowrap><?= GetMessage("TC_OBJECT_ID") ?>:</td>
    	<td nowrap><input type="text" name="find_object_id" value="<?echo htmlspecialchars($find_object_id)?>" size="44"></td>
    </tr>



    <tr>
    	<td nowrap><?= GetMessage("TC_DATE") ?>:</td>
    	<td nowrap><input type="text" name="find_date" size="44"><?=Calendar("find_date", "form2")?></td>
    </tr>

    <tr>
    	<td nowrap><?= GetMessage("TC_SITEID") ?>:</td>
    	<td nowrap>
        <?

            $arr = array("reference" => array(),"reference_id" => array());

            $rsSites = CSite::GetList($by="sort", $order="desc", Array());



            while ($arSite = $rsSites->GetNext())

            {

              $arr["reference"][] = $arSite['LID'];

              $arr["reference_id"][] = $arSite['LID'];

            }

            echo SelectBoxFromArray("find_site_id", $arr, $find_site_id, "", "");

        ?>
        </td>
    </tr>

    <tr>
    	<td nowrap><?= GetMessage("TC_ACTIVATED") ?>:</td>
    	<td nowrap><input type="text" name="find_activated" value="<?echo htmlspecialchars($find_activated)?>" size="44"></td>
    </tr>

		<tr>
    	<td nowrap><?= GetMessage("TC_DATE_MODIFY") ?>:</td>
    	<td nowrap><input type="text" name="find_date_modify" size="44"><?=Calendar("find_date_modify", "form2")?></td>
    </tr>

    <?$filter->Buttons(array("table_id"=>$sTableID, "url"=>$APPLICATION->GetCurPage(), "form"=>"form2"));?>
    <?$filter->End();?>
    </form>
    <?

    $lAdmin->DisplayList();

}

function getNumericSuffix($values, $count, $base = 10)
{
	// prepare values array
	$countValues = count($values);
	if ($countValues == count($values, COUNT_RECURSIVE))
	{
		if ($countValues == 3 && $base == 10)
		{
			$values = array(
				$values[0] => array(
					"BALANCE" => 1
				),
				$values[1] => array(
					"BALANCE" => array(
						2 => 4
					)
				),
				$values[2] => array(
					"BALANCE" => array(
						0,
						5 => 9
					),
					"RANGE" => array(
						10 => 20
					),
					"RANGE_PERIOD" => 100
				)
			);
		}
		else
		{
			return;
		}
	}

	$result = false;
	$base = intval($base);
	$base = $base > 0 ? $base : 10;
	$balance = $count % $base;

	foreach ($values as $suffix => $options)
	{
		// try to find $count in RANGE option.
		if (!empty($options['RANGE']))
			foreach ($options['RANGE'] as $min => $max)
			{
				$c = $count;
				if (!empty($options['RANGE_PERIOD']))
					$c = $count % $options['RANGE_PERIOD'];
				if ($c >= $min && $c <= $max)
					return $suffix;
			}

		// try to find $count in BALANCE option.
		if (!empty($options['BALANCE']))
			if (is_array($options['BALANCE']))
				foreach ($options['BALANCE'] as $min => $max)
				{
					if (isset($options['BALANCE'][$min]))
						if ($balance >= $min && $balance <= $max)
							$result = $suffix;
						else if ($balance == $min)
							$result = $suffix;
				}
			else if ($options['BALANCE'] == $balance)
				$result = $suffix;
	}

	return $result;
}

require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");

?>