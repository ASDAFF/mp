<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?

if (CModule::IncludeModuleEx('prmedia.treelikecomments') === MODULE_DEMO_EXPIRED)
{
	echo '<div style="border: solid 1px #000; padding: 5px; font-weight: bold; color: #ff0000;">';
	echo GetMessage('PRMEDIA_TREELIKE_COMMENTS_DEMO_EXPIRED');
	echo '</div>';
	return;
}

// check modules
if (!CModule::IncludeModule('prmedia.treelikecomments') || !CModule::IncludeModule('iblock'))
{
	return;
}

if ($this->StartResultCache($arParams['CACHE_TIME']))
{
	$arResult = array(
		'TITLE' => $arParams['TITLE'],
		'SHOW_TEXT' => $arParams['SHOW_TEXT']
	);

	// restrictions
	$arRestrictons = array();
	if (PrmediaLastCommentsCheckRestrictions($arParams['IBLOCK_RESTRICTIONS']))
	{
		$arRestrictons = $arParams['IBLOCK_RESTRICTIONS'];
	}
	$restrictions = implode(',', $arRestrictons);

	// getlist
	$order = array(
		'ID' => 'DESC'
	);
	$filter = array(
		'SITE_ID' => SITE_ID,
		'ACTIVATED' => 1,
		'IBLOCK_ID' => $restrictions
	);
	$rsComment = CTreelikeComments::GetList($order, $filter, intval($arParams['COUNT']));
	while ($arComment = $rsComment->GetNext())
	{
		$element = CIBlockElement::GetByID($arComment['OBJECT_ID']);
		$arIblockElement = $element->GetNext();
		if (!strlen($arComment['LOGIN']))
		{
			$username = $arComment['AUTHOR_NAME'];
			$isRegistered = 'N';
			$profileURL = '';
		}
		else
		{
			$username = $arComment['LOGIN'];
			if (strlen($arParams['PROFILE_URL']))
			{
				$isRegistered = 'Y';
				// profile url
				$profileURL = $arParams['PROFILE_URL'];
				if (preg_match('/USER_LOGIN/i', $profileURL))
				{
					$userlogin_before = "#USER_LOGIN#";
					$userlogin_after = $arComment['LOGIN'];
					$profileURL = str_replace($userlogin_before, $userlogin_after, $profileURL);
				}
				if (preg_match('/USER_ID/i', $profileURL))
				{
					$id_before = "#USER_ID#";
					$id_after = $arComment['USER_ID'];
					$profileURL = str_replace($id_before, $id_after, $profileURL);
				}
			} else
			{
				$isRegistered = 'N';
			}
		}

		$detailURL = $arParams['DETAIL_PAGE_URL'];
		if (preg_match('/ID/i', $detailURL))
			$detailURL = str_replace('#ID#', $arIblockElement['ID'], $detailURL);

		if (preg_match('/CODE/i', $detailURL))
			$detailURL = str_replace('#CODE#', $arIblockElement['CODE'], $detailURL);

		if (preg_match('/DETAIL_PAGE_URL/i', $detailURL))
			$detailURL = str_replace('#DETAIL_PAGE_URL#', $arIblockElement['DETAIL_PAGE_URL'], $detailURL);

		if (preg_match('/SECTION_ID/i', $detailURL))
			$detailURL = str_replace('#SECTION_ID#', $arIblockElement['IBLOCK_SECTION_ID'], $detailURL);

		if (preg_match('/SECTION_CODE/i', $detailURL))
		{
			$section = CIBlockSection::GetByID($arIblockElement['IBLOCK_SECTION_ID']);
			if ($arSection = $section->GetNext())
				$detailURL = str_replace('#SECTION_CODE#', $arSection['CODE'], $detailURL);
		}

		if (strlen($arComment['~COMMENT']) > $arParams['TEXT_LENGTH'])
			$end = "...";
		else
			$end = "";

		if ($arResult['SHOW_TEXT'] == "Y" && intval($arParams['TEXT_LENGTH']) > 0)
			$arComment['~COMMENT'] = mb_substr($arComment['~COMMENT'], 0, $arParams['TEXT_LENGTH']) . $end;

		$detailURL = str_replace('%2F', '/', $detailURL);
		$arResult['ITEMS'][] = array(
			'ID' => $arComment['ID'],
			'ELEMENT_NAME' => $arIblockElement['NAME'],
			'USER_NAME' => $username,
			'ELEMENT_ID' => $arComment['OBJECT_ID'],
			'IS_REGISTERED' => $isRegistered,
			'DETAIL_PAGE_URL' => $detailURL,
			'PROFILE_URL' => $profileURL,
			'DATE' => $arComment['NEW_DATE'],
			'TEXT' => $arComment['~COMMENT']
		);
	}

	$this->IncludeComponentTemplate();
}


// functions
function PrmediaLastCommentsCheckRestrictions($arRest = array())
{
	if (empty($arRest)) return false;

	foreach ($arRest as $oneRest)
	{
		if ($oneRest == 'N') return false;
	}

	return true;
}
?>