<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

// demo version check
if (CModule::IncludeModuleEx("prmedia.treelikecomments") == MODULE_DEMO_EXPIRED)
{
  echo '<div style="border: solid 1px #000; padding: 5px; font-weight:bold; color: #ff0000;">';
	echo GetMessage('PRMEDIA_TREELIKE_COMMENTS_DEMO_EXPIRED');
	echo '</div>';
	return;
}

// check params
if (!CModule::IncludeModule("iblock"))
{
	// @todo show error (module IBLOCK is not installed)
	return;
}
$arParams["OBJECT_ID"] = intval($arParams["OBJECT_ID"]);
if(!$arParams["OBJECT_ID"])
{
	// @todo show error (OBJECT ID is not exists)
	return;
}
$arParams["NES_RATING"] = intval($arParams["NES_RATING"]);
if($arParams["NES_RATING"] <= 0)
{
	ShowError(GetMessage("PRMEDIA_BC_WRONG_RATING"));
	return;
}
$arParams["NES_COMMENTS_COUNT"] = intval($arParams["NES_COMMENTS_COUNT"]);
global $APPLICATION, $USER, $CACHE_MANAGER;
CJSCore::Init("jquery");

// caching params
$arParams['IS_ANONYM'] = intval(CUser::GetID()) ? true : false;
$cache_id = md5(serialize($arParams));
$cache_dir = "/".SITE_ID.'/'.str_replace(':', '/', $this->GetName())."/".$arParams["OBJECT_ID"];

// caching
if($this->StartResultCache(false, $cache_id, $cache_dir))
{
	$CACHE_MANAGER->RegisterTag("bestcomments_".$arParams["OBJECT_ID"]);

	$arResult['ALLOW_RATING'] = $arParams['ALLOW_RATING'];
	$arResult['SHOW_USERPIC'] = $arParams['SHOW_USERPIC'];
	$arResult['SHOW_DATE'] = $arParams['SHOW_DATE'];
	$arResult['GROUPS'] = CTreelikeComments::IsModerator();
	$arResult['CURRENT_USER'] = $USER->IsAuthorized() ? $USER->GetID() : 0;

	$arItems = array();
	$arIDs = array();

	// Getting comments List
	$arFilter = array(
		"OBJECT_ID_NUMBER" => $arParams["OBJECT_ID"],
		"SITE_ID" => SITE_ID,
	);

	$resComm = CTreelikeComments::GetList(array(), $arFilter);
	while($arComm = $resComm->GetNext()):
		$arIDs[] = $arComm["ID"];
		$arItems[$arComm["ID"]] = $arComm;
		$arItems[$arComm["ID"]]["COMMENT"] = $arComm["~COMMENT"];
	endwhile;

	// Count votes
	if(!empty($arIDs))
	{
		$resVoteIDs = CTreelikeComments::GetVotedIDs($arIDs);
		$allbestCommentsCount = 0; // corresponding to the condition

		while($arIDs = $resVoteIDs->GetNext()):
			if($arIDs["VOTE_TYPE"] == "UP")
			{
				if($arIDs["COUNT"] > 0)
					$arItems[$arIDs["COMMENT_ID"]]["VoteUp"] = $arIDs["COUNT"];
				else
					$arItems[$arIDs["COMMENT_ID"]]["VoteUp"] = 0;
			}
			if($arIDs["VOTE_TYPE"] == "DOWN")
			{
				if($arIDs["COUNT"] > 0)
					$arItems[$arIDs["COMMENT_ID"]]["VoteDown"] = $arIDs["COUNT"];
				else
					$arItems[$arIDs["COMMENT_ID"]]["VoteDown"] = 0;
			}

			$arItems[$arIDs["COMMENT_ID"]]["TOTAL_VOTE"] = (($arItems[$arIDs["COMMENT_ID"]]["VoteDown"] * -1)
			+ $arItems[$arIDs["COMMENT_ID"]]["VoteUp"]);

		endwhile;
	}


	// Deleting unnecessary items
	foreach($arItems as $key => $oneItem):
		if($oneItem["TOTAL_VOTE"] < $arParams["NES_RATING"])
			unset($arItems[$key]);
		else
			$allbestCommentsCount++;
	endforeach;

	foreach($arItems as $key => $oneItem):

		$arUser = array();

		if($oneItem["USER_ID"])
		{
			$link = $arParams["TO_USERPAGE"];
			if(preg_match('/USER_LOGIN/i', $link))
            	$link = str_replace("#USER_LOGIN#", $oneItem["LOGIN"], $link);

			if(preg_match('/USER_ID/i', $arParams["TO_USERPAGE"]))
            	$link = str_replace("#USER_ID#", $oneItem["USER_ID"], $link);
		}

		$arUser = array(
			"LOGIN" => $oneItem['LOGIN'],
			"NAME" => $oneItem['NAME'],
			"LAST_NAME" => $oneItem['LAST_NAME'],
			"PERSONAL_PHOTO" => CFile::GetPath($oneItem['PERSONAL_PHOTO']),
			"USERLINK" => $link
		);

		switch($arParams["ASNAME"])
		{
			case "name_lastname":
				if($arUser['NAME'] != "" && $arUser['LAST_NAME'] != "")
					$arUser['LOGIN'] = $arUser['NAME']." ".$arUser['LAST_NAME'];
			break;
			case "name":
				if($arUser['NAME'] != "")
					$arUser['LOGIN'] = $arUser['NAME'];
			break;
		}

		$arItems[$key]["USER"] = $arUser;
		$arItems[$key]["COMMENT_LINK"] = ($arParams["SHOW_COMMENT_LINK"] == "Y") ? "comment_".$oneItem["ID"] : "N";
		$arItems[$key]["DATE_CREATE"] = FormatDate($arParams['DATE_FORMAT'], strtotime($oneItem['NEW_DATE']));

	endforeach;

	// There's no comments corresponding to the condition
	if($allbestCommentsCount == 0)
	{
		return $this->IncludeComponentTemplate('empty');
	}

	// Generating defining array
	$arTotal = array();
	foreach($arItems as $key => $oneItem)
		$arTotal[$key] = $oneItem['TOTAL_VOTE'];

	// Sorting
	array_multisort($arTotal, SORT_NUMERIC, SORT_DESC, $arItems);
	$arItems = array_slice($arItems, 0, $arParams["NES_COMMENTS_COUNT"]);

	$arResult["COMMENTS"] = $arItems;

	$this->IncludeComponentTemplate();

}

?>