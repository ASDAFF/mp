<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
if (CModule::IncludeModuleEx('prmedia.treelikecomments') != MODULE_DEMO_EXPIRED)
{

	if ($this->StartResultCache($arParams['CACHE_TIME']))
	{
		CModule::IncludeModule("prmedia.treelikecomments");
		CModule::IncludeModule("iblock");

		$arResult['TITLE'] = $arParams['TITLE'];

		$res = CTreelikeComments::getPopularElements($arParams['COUNT']);
		while ($arRes = $res->GetNext())
		{
			$element = CIBlockElement::GetByID($arRes['OBJECT_ID']);
			if ($arElement = $element->GetNext())
			{

				$detailURL = $arParams['DETAIL_PAGE_URL'];
				if (preg_match('/ID/i', $detailURL))
					$detailURL = str_replace('#ID#', $arElement['ID'], $detailURL);
				if (preg_match('/CODE/i', $detailURL))
					$detailURL = str_replace('#CODE#', $arElement['CODE'], $detailURL);
				if (preg_match('/DETAIL_PAGE_URL/i', $detailURL))
					$detailURL = str_replace('#DETAIL_PAGE_URL#', $arElement['DETAIL_PAGE_URL'], $detailURL);
				if (preg_match('/SECTION_ID/i', $detailURL))
					$detailURL = str_replace('#SECTION_ID#', $arElement['IBLOCK_SECTION_ID'], $detailURL);
				if (preg_match('/SECTION_CODE/i', $detailURL))
				{
					$section = CIBlockSection::GetByID($arElement['IBLOCK_SECTION_ID']);
					if ($arSection = $section->GetNext())
						$detailURL = str_replace('#SECTION_CODE#', $arSection['CODE'], $detailURL);
				}


				if ($arParams['IMAGE'] == 'NOT_SHOW')
					$image = '';
				elseif ($arParams['IMAGE'] == 'PREVIEW_PICTURE')
					$image = CFile::GetPath($arElement['PREVIEW_PICTURE']);
				elseif ($arParams['IMAGE'] == 'DETAIL_PICTURE')
					$image = CFile::GetPath($arElement['DETAIL_PICTURE']);
				else
				{
					$db_props = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], array("sort" => "asc"), Array("CODE" => $arParams['IMAGE']));
					if ($arProps = $db_props->Fetch())
						$image = CFile::GetPath($arProps["VALUE"]);
					else
						$image = '';
				}

				$arResult['ITEMS'][] = array(
					'ELEMENT_ID' => $arElement['ID'],
					'ELEMENT_NAME' => $arElement['NAME'],
					'DETAIL_PAGE_URL' => $detailURL,
					'IMAGE' => $image,
					'COMMENTS_COUNT' => $arRes['COMMENTS_COUNT']
				);
			}
		}
		$this->IncludeComponentTemplate();
	}
}
else
{
	echo '<div style="border: solid 1px #000; padding: 5px; font-weight:
    bold; color: #ff0000;">' . GetMessage('PRMEDIA_TREELIKE_COMMENTS_DEMO_EXPIRED') . '</div>';
}
?>