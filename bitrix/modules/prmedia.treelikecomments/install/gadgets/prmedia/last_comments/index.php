<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
global $APPLICATION, $CACHE_MANAGER;

CJSCore::Init(array('jquery'));

// logic
CModule::IncludeModule('prmedia.treelikecomments');
CModule::IncludeModule('iblock');
if (empty($arGadgetParams['COUNT']))
{
	$arGadgetParams['COUNT'] = 10;
}

// delete comment
if (!empty($_GET['delete-comment-id']))
{
	$commentId = intval($_GET['delete-comment-id']);
	$obComment = new CTreelikeComments;

	$rsComment = $obComment->GetByID($commentId);
	if ($arComment = $rsComment->Fetch())
	{
		$obComment->Delete($commentId);
		$CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_" . $arComment['OBJECT_ID']);
	}

	LocalRedirect($APPLICATION->GetCurPageParam("", array("delete-comment-id")));
}


$arResult = array();
$rsComment = CTreelikeComments::GetList(array(), array(), intval($arGadgetParams['COUNT']));
$count = CTreelikeComments::GetList(array(), array(), intval($arGadgetParams['COUNT']), true);
if ($count > $arGadgetParams['COUNT'])
{
	$count = $arGadgetParams['COUNT'];
}
while ($arComment = $rsComment->GetNext())
{
	$arItem = array();
	$rsElement = CIBlockElement::GetByID($arComment['OBJECT_ID']);
	$arElement = $rsElement->GetNext();
	if (empty($arComment['LOGIN']))
	{
		$arItem['LOGIN'] = '<strong>' . $arComment['AUTHOR_NAME'] . '</strong>';
	} else
	{
		$arItem['LOGIN'] = '<a href="/bitrix/admin/user_edit.php?ID=' . $arComment['USER_ID'] . '" style="font-weight: bold;">' . $arComment['LOGIN'] . '</a>';
	}

	$arItem['IBLOCK_TYPE_ID'] = $arElement['IBLOCK_TYPE_ID'];
	$arItem['ID'] = $arComment['ID'];
	$arItem['NAME'] = $arElement['NAME'];
	$arItem['IBLOCK_ID'] = $arElement['IBLOCK_ID'];
	$arItem['COMMENT'] = $arComment['~COMMENT'];

	// DATETIME
	$arDateTime = split(" ", $arComment['NEW_DATE']);
	$arItem['DATE'] = $arDateTime[0];
	$arItem['TIME'] = $arDateTime[1];

	$arItem['DELETE_URL'] = $APPLICATION->GetCurPageParam("delete-comment-id=" . $arItem['ID'], array('delete-comment-id'));

	// DETAIL URL
	$detailURL = $arGadgetParams['DETAIL_PAGE_URL'];
	if (preg_match('/ID/i', $detailURL))
	{
		$detailURL = str_replace('#ID#', $arElement['ID'], $detailURL);
	}
	if (preg_match('/CODE/i', $detailURL))
	{
		$detailURL = str_replace('#CODE#', $arElement['CODE'], $detailURL);
	}
	if (preg_match('/DETAIL_PAGE_URL/i', $detailURL))
	{
		$detailURL = str_replace('#DETAIL_PAGE_URL#', $arElement['DETAIL_PAGE_URL'], $detailURL);
	}
	if (preg_match('/SECTION_ID/i', $detailURL))
	{
		$detailURL = str_replace('#SECTION_ID#', $arElement['IBLOCK_SECTION_ID'], $detailURL);
	}
	if (preg_match('/SECTION_CODE/i', $detailURL))
	{
		$section = CIBlockSection::GetByID($arElement['IBLOCK_SECTION_ID']);
		if ($arSection = $section->GetNext())
		{
			$detailURL = str_replace('#SECTION_CODE#', $arSection['CODE'], $detailURL);
		}
	}
	$arItem['DETAIL_URL'] = str_replace('%2F', '/', $detailURL);


	$arResult['ITEMS'][] = $arItem;
	$count--;
	if ($count == 0)
	{
		break;
	}
}

if (empty($arResult['ITEMS']))
{
	return;
}
$arResult['ITEMS'][end(array_keys($arResult['ITEMS']))]['LAST'] = "Y";

$APPLICATION->SetAdditionalCSS('/bitrix/gadgets/prmedia/last_comments/style.css');
?>

<div class="bx-gadgets-info prmedia-last-comments">
	<table class="bx-gadgets-info-site-table">
		<tr>
			<td align="left" valign="top">
				<div class="bx-gadgets-text">
					<? foreach ($arResult['ITEMS'] as $arItem): ?>
						<form style="position: relative;" method="POST" action="<?= $arItem['DELETE_URL'] ?>">
							<span class="delete-comment"></span>
							<p style="padding-left: 15px;"><?= $arItem['DATE'] ?> <?= GetMessage('COMMENTS_TIME') ?> <?= $arItem['TIME'] ?></p>
							<img src="/bitrix/gadgets/prmedia/last_comments/images/userpic.png" alt="" height="11" /> <?= $arItem['LOGIN'] ?> <?= GetMessage('LEAVE_COMMENT') ?> <a href="/bitrix/admin/iblock_element_edit.php?ID=<?= $arItem['OBJECT_ID'] ?>&type=<?= $arItem['IBLOCK_TYPE_ID'] ?>&lang=<?= LANGUAGE_ID ?>&IBLOCK_ID=<?= $arItem['IBLOCK_ID'] ?>&find_section_section=0"><?= $arItem['NAME'] ?></a>:
							<p style="padding-left: 15px;"><?= $arItem['COMMENT'] ?></p>
							<? if (empty($arItem['LAST'])): ?>
								<div style="height: 1px; background-color: #ebebeb; font-size: 0; margin-bottom: 10px;"></div>
							<? endif; ?>
						</form>
					<? endforeach ?>
				</div>
			</td>
		</tr>
	</table>
</div>

<script type="text/javascript">
	$(".prmedia-last-comments .delete-comment").click(function() {
		var result = confirm("<?= GetMessage('COMMENTS_DELETE_CONFIRM') ?>");
		if (result) {
			$(this).closest('form').submit();
		}
	});
</script>