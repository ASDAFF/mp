<?

global $APPLICATION;
global $DB;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/prmedia.treelikecomments/include.php");

require($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/prolog_admin_after.php");

global $CACHE_MANAGER;
global $USER;

$obComment = new CTreelikeComments;

if ($obComment->IsModerator())
{
	$action = trim($_GET['action']);
	$id_element = intval($_GET['id']);

	if (!isset($action) || !isset($id_element))
	{
		LocalRedirect('/bitrix/admin/tc_comment_list.php?result=error');
	}

	if ($action == 'activate' && isset($id_element))
	{
		$DB->query("UPDATE prmedia_treelike_comments SET ACTIVATED = 1 WHERE ID = $id_element");

		$objectId = $obComment->GetObjectData($id_element);
		@$CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_" . $objectId);

		LocalRedirect('/bitrix/admin/tc_comment_list.php?result=activated');
	}

	if ($action == 'deactivate' && isset($id_element))
	{
		$DB->query("UPDATE prmedia_treelike_comments SET ACTIVATED = 0 WHERE ID = $id_element");

		$objectId = $obComment->GetObjectData($id_element);
		@$CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_" . $objectId);

		LocalRedirect('/bitrix/admin/tc_comment_list.php?result=deactivated');
	}

	if (in_array($action, array("ban", "ban_delete")) && isset($id_element))
	{
		// add user ip into ban
		$elementId = 5;
		$obComment = new CTreelikeComments;
		$rsComment = $obComment->GetByID($id_element);
		if ($arComment = $rsComment->Fetch())
		{
			$addr = $arComment['REMOTE_ADDR'];
			$ips = trim(COption::GetOptionString("prmedia.treelikecomments", "ban", ""));
			$arIPs = explode(",", $ips);
			foreach ($arIPs as $key => $ip)
			{
				$ip = trim($ip);
				if (empty($ip))
				{
					unset($arIPs[$key]);
					continue;
				}
				$arIPs[$key] = trim($ip);
				if ($ip == $addr)
				{
					unset($addr);
				}
			}
			if (!empty($addr))
			{
				$arIPs[] = $addr;
			}
			COption::SetOptionString("prmedia.treelikecomments", "ban", implode(", ", $arIPs));

			$addr = $arComment['REMOTE_ADDR'];
			if ($action == 'ban_delete')
			{
				// delete all comments for ip
				$arFilter = array(
					"REMOTE_ADDR" => $addr
				);
				$rsComment = $obComment->GetList(false, $arFilter);
				while ($arComment = $rsComment->Fetch())
				{
					$objectId = $obComment->GetObjectData($arComment['ID']);
					$obComment->Delete($arComment['ID']);
					@$CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_" . $objectId);
				}
				LocalRedirect('/bitrix/admin/tc_comment_list.php?result=ban_delete');
				return;
			}
			LocalRedirect('/bitrix/admin/tc_comment_list.php?result=ban');
		}
	}
}
else
{
	LocalRedirect('/bitrix/admin/tc_comment_list.php');
}


require($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog_admin.php");
?>