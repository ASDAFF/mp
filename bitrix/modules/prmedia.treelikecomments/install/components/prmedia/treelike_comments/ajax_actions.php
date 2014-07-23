<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/prmedia/treelike_comments/lang/ru/ajax_actions.php");
?>

<?

$MODULE_ID = "prmedia.treelikecomments";
CModule::IncludeModule($MODULE_ID);

$AxObject = new CTreelikeComments;
global $CACHE_MANAGER;
global $USER;


// activate (moderator action)
if(isset($_POST['ACTIVATE_ON']))
{
    $objectID = $AxObject->GetObjectData($_POST['ACTIVATE_ON']);
    $AxObject->Activate($_POST['ACTIVATE_ON']);
    @$CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_".$objectID);
}


// vote up
if(isset($_POST['VoteUp']))
{
	if(!$AxObject->IsUserVoted($_POST['VoteUp'], $USER->GetID()))
	{
		$users = array(
			"USER_ID" => $USER->GetID(),
			"COMMENT_ID" => $_POST['VoteUp'],
			"VOTE_TYPE" => 'UP'
		);

		$AxObject->Vote($users);

		$objectID = $AxObject->GetObjectData($_POST['VoteUp']);
		@$CACHE_MANAGER->ClearByTag("bestcomments_".$objectID);
		@$CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_".$objectID);
		echo 1;
		return;
	}
	else
	{
		echo GetMessage('YOU_HAVE_BEEN_VOTED');
		return;
	}
}


// vote down
if(isset($_POST['VoteDown']))
{
	if(!$AxObject->IsUserVoted($_POST['VoteDown'], $USER->GetID()))
	{
		$users = array(
			"USER_ID" => $USER->GetID(),
			"COMMENT_ID" => $_POST['VoteDown'],
			"VOTE_TYPE" => 'DOWN'
		);

		$AxObject->Vote($users);
		$objectID = $AxObject->GetObjectData($_POST['VoteDown']);
		@$CACHE_MANAGER->ClearByTag("bestcomments_".$objectID);
		@$CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_".$objectID);
		echo 1;
		return;
	}
	else
	{
		echo GetMessage('YOU_HAVE_BEEN_VOTED');
		return;
	}
}


//
if(isset($_POST['SENDING']))
{
	if($_POST['SENDING'] == 1)
	{
		$to_Mail_activated = $AxObject->AuthorMail($_POST['ACTIVATE_ON']);
		if($to_Mail_activated != 'N')
		{
			$mail_sender = COption::GetOptionString($MODULE_ID, 'mail_sender');
			$element_id = intval($AxObject->GetObjectData($_POST['ACTIVATE_ON']));

			CModule::IncludeModule("iblock");
			$rsElement = CIBlockElement::GetByID($element_id);
			if($arElement = $rsElement->GetNext())
			{
				$detail_url = $arElement['DETAIL_PAGE_URL'];
				$element_name = $arElement['NAME'];
				$element_code = $arElement['CODE'];
				$detail_url = str_replace('%2F', '/', $detail_url);
			}

			$rsSites = CSite::GetByID(SITE_ID);
			if ($arSite = $rsSites->Fetch())
			{
				$siteName = $arSite["NAME"];
			}

			$arEmailFieldsActivated = array(
				"COMMENT_ID" => intval($_POST['ACTIVATE_ON']),
				"USER_ID" => intval($AxObject->GetUserID($_POST['ACTIVATE_ON'])),
				"ELEMENT_ID" => $element_id,
				"ELEMENT_CODE" => $element_code,
				"ELEMENT_NAME" => trim($element_name),
				"DETAIL_PAGE_URL" => trim($detail_url),
				"USER_LOGIN" => trim($AxObject->GetUserLogin($_POST['ACTIVATE_ON'])),
				"EMAIL_FROM" => $mail_sender,
				"EMAIL_TO" => $to_Mail_activated,
				"SITE_NAME" => $siteName
			);

			CEvent::Send("PRMEDIA_TC_ACTIVATE", SITE_ID, $arEmailFieldsActivated, false);
		}
	}
}


if (isset($_POST['SEND_AFTER_ANSWER']))
{
	if ($_POST['SEND_AFTER_ANSWER'] == 1)
	{

		$to_Mail_answer = $AxObject->IsAnswered($_POST['COMMENT_ID']);

		$element_id = intval($AxObject->GetObjectData($_POST['COMMENT_ID']));
		$mail_sender = COption::GetOptionString($MODULE_ID, 'mail_sender');

		CModule::IncludeModule("iblock");
		$res = CIBlockElement::GetByID($element_id); // GETTING DATA OF THE COMMENT WHICH WAS COMMENTED

		if ($ar_res = $res->GetNext())
		{
			$detail_url = $ar_res['DETAIL_PAGE_URL'];
			$element_name = $ar_res['NAME'];
			$element_code = $ar_res['CODE'];
		}

		$rsSites = CSite::GetByID(SITE_ID);
		if ($arSite = $rsSites->Fetch())
			$siteName = $arSite["NAME"];

		$arEmailFieldsAnswering = array(
			"COMMENT_ID" => intval($_POST['COMMENT_ID']),
			"COMMENT_TEXT" => trim($AxObject->GetCommentText($_POST['COMMENT_ID'])),
			"USER_ID" => intval($AxObject->GetUserID($_POST['COMMENT_ID'])),
			"ELEMENT_ID" => intval($element_id),
			"ELEMENT_CODE" => $element_code,
			"ELEMENT_NAME" => $element_name,
			"DETAIL_PAGE_URL" => $detail_url,
			"USER_LOGIN" => trim($AxObject->GetUserLogin($_POST['COMMENT_ID'])),
			"PARENT_ID" => intval($AxObject->getParentID($_POST['COMMENT_ID'])),
			"EMAIL_FROM" => $mail_sender,
			"EMAIL_TO" => $to_Mail_answer,
			"SITE_NAME" => $siteName
		);

		CEvent::Send("PRMEDIA_TC_USER", SITE_ID, $arEmailFieldsAnswering, false);
	}
}


if (isset($_POST['SEND_AFTER_MENTION']))
{
	if ($_POST['SEND_AFTER_MENTION'] == 1)
	{

		$to_Mail_like = $AxObject->IsNameFound($_POST['COMMENT_ID']);

		$element_id = $AxObject->GetObjectData($_POST['COMMENT_ID']);
		$mail_sender = COption::GetOptionString($MODULE_ID, 'mail_sender');

		$user_like_data = $AxObject->GetByMail($to_Mail_like);

		if ($arUser_like = $user_like_data->Fetch())
		{
			$user_id_like = $arUser_like['ID'];
			$user_login_like = $arUser_like['LOGIN'];
		}

		CModule::IncludeModule("iblock");
		$res = CIBlockElement::GetByID($element_id); // GETTING DATA OF THE COMMENT WHICH WAS COMMENTED

		if ($ar_res = $res->GetNext())
		{
			$detail_url = $ar_res['DETAIL_PAGE_URL'];
			$element_name = $ar_res['NAME'];
			$element_code = $ar_res['CODE'];
		}

		$rsSites = CSite::GetByID(SITE_ID);
		if ($arSite = $rsSites->Fetch())
			$siteName = $arSite["NAME"];

		$arEmailFieldsLike = array(
			"USER_ID" => intval($user_id_like),
			"USER_LOGIN" => trim($user_login_like),
			"AUTHOR_ID" => intval($AxObject->GetUserID($_POST['COMMENT_ID'])),
			"AUTHOR_LOGIN" => trim($AxObject->GetUserLogin($_POST['COMMENT_ID'])),
			"COMMENT_ID" => intval($_POST['COMMENT_ID']),
			"COMMENT_TEXT" => trim($AxObject->GetCommentText($_POST['COMMENT_ID'])),
			"ELEMENT_ID" => intval($element_id),
			"ELEMENT_CODE" => $element_code,
			"ELEMENT_NAME" => $element_name,
			"DETAIL_PAGE_URL" => $detail_url,
			"EMAIL_FROM" => $mail_sender,
			"EMAIL_TO" => $to_Mail_like,
			"SITE_NAME" => $siteName
		);

		CEvent::Send("PRMEDIA_TC_USER_MENTION", SITE_ID, $arEmailFieldsLike, false);
	}
}


// delete (moderator action)
if(isset($_POST['D_ID']))
{
	$objectID = $AxObject->GetObjectData($_POST['D_ID']);
	$AxObject->Delete($_POST['D_ID'], false);
	@$CACHE_MANAGER->ClearByTag("bestcomments_".$objectID);
	@$CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_".$objectID);
}


// delete (moderator action) // @todo dublicate prev block (join $_POST variables into the one block)
if(isset($_POST['ALL_ID']))
{
	$objectID = $AxObject->GetObjectData($_POST['ALL_ID']);
	$AxObject->Delete($_POST['ALL_ID'], true);
	@$CACHE_MANAGER->ClearByTag("bestcomments_".$objectID);
	@$CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_".$objectID);
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>