<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?

// component requirements
$MODULE_ID = "prmedia.treelikecomments";
if (CModule::IncludeModuleEx($MODULE_ID) == MODULE_DEMO_EXPIRED)
{
	echo '<div style="border: solid 1px #000; padding: 5px; font-weight: bold; color: #ff0000;">';
	echo GetMessage('PRMEDIA_TREELIKE_COMMENTS_DEMO_EXPIRED');
	echo '</div>';
	return;
}
if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule($MODULE_ID))
{
	return;
}


// --- --- --- @todo move smiles from template folder to component folder
if (!$this->InitComponentTemplate())
{
	return;
}
$template = &$this->GetTemplate();
$templateFolder = $template->GetFolder();
// --- --- ---


// variables processing
$arParams['OBJECT_ID'] = intval($arParams['OBJECT_ID']);
if(!$arParams['OBJECT_ID'])
{
	$x = CIBlockElement::GetList(false, array(
		'IBLOCK_ID' => 1,
		'CODE' => $_REQUEST['ELEMENT_CODE']
		), false, false, array(
		'ID'
		))->Fetch();
	$arParams["OBJECT_ID"] = $x['ID'];
	// ShowError(GetMessage("OBJECT_ID_NOT_DEFINED"));
	// return;
}
define(OBJECT_ID, $arParams['OBJECT_ID']);
$arFilter = array(
	"ID" => $arParams['OBJECT_ID']
);
$arSelect = array(
	"IBLOCK_ID"
);
$rsElement = CIBlockElement::GetList(false, $arFilter, false, false, $arSelect);
if($arElement = $rsElement->Fetch())
{
	$iblockId = $arElement["IBLOCK_ID"];
}
if(!$iblockId)
{
	ShowError(GetMessage("IBLOCK_NOT_FOUND"));
	return;
}

// initialize result array
global $USER, $APPLICATION, $CACHE_MANAGER;
$comment = new CTreelikeComments;

// @todo move result array definition into cache block
$arResult = array();
$arResult['DATE_FORMAT'] = $arParams['DATE_FORMAT'];
$arResult['PREMODERATION'] = ($arParams['PREMODERATION']) ? $arParams['PREMODERATION'] : "N";
$arResult['AUTH_PATH'] = trim($arParams['AUTH_PATH']);
$arResult['TO_USERPAGE'] = trim($arParams['TO_USERPAGE']);
$arResult['LEFT_MARGIN'] = intval($arParams['LEFT_MARGIN']);
$arResult['NO_INDEX'] = $arParams['NO_INDEX'];
$arResult['NO_FOLLOW'] = $arParams['NO_FOLLOW'];
$arResult['SHOW_USERPIC'] = $arParams['SHOW_USERPIC'];
$arResult['SHOW_DATE'] = $arParams['SHOW_DATE'];
$arResult['SHOW_COUNT'] = $arParams['SHOW_COUNT'];
$arResult["SHOW_COMMENT_LINK"] = $arParams["SHOW_COMMENT_LINK"];
$arResult['SHOW_FILEMAN'] = ($arParams['SHOW_FILEMAN'] == "no") ? 0 : 1;
$arResult['MAX_DEPTH_LEVEL'] = intval($arParams['MAX_DEPTH_LEVEL']);
$arResult['ALLOW_RATING'] = $arParams['ALLOW_RATING'];
$arResult['ALLOW_SMILES'] = COption::GetOptionString($MODULE_ID, 'smiles_enable');
$arResult['ALLOW_BBCODES'] = COption::GetOptionString($MODULE_ID, 'bb_code_enable');
$arResult['SEND_TO_USER_AFTER_ANSWERING'] = $arParams['SEND_TO_USER_AFTER_ANSWERING'];
$arResult['SEND_TO_USER_AFTER_MENTION_NAME'] = $arParams['SEND_TO_USER_AFTER_MENTION_NAME'];
$arResult['SEND_TO_ADMIN_AFTER_ADDING'] = $arParams['SEND_TO_ADMIN_AFTER_ADDING'];
$arResult['SEND_TO_USER_AFTER_ACTIVATE'] = $arParams['SEND_TO_USER_AFTER_ACTIVATE'];
$arResult['NON_AUTHORIZED_USER_CAN_COMMENT'] = $arParams['NON_AUTHORIZED_USER_CAN_COMMENT'];
$arResult['CAPTCHA_TYPE'] = ($arParams['USE_CAPTCHA']) ? $arParams['USE_CAPTCHA'] : "NO";
if($arResult["CAPTCHA_TYPE"] == "Y")
{
	$arResult["CAPTCHA_TYPE"] = "CAPTCHA_BITRIX";
}
$arResult["NO_CAPTCHA"] = "N"; // need of a captcha
$arResult['ISADMIN'] = $USER->IsAdmin();
if(strlen($arParams['ASNAME']) > 0)
{
	$arResult['ASNAME'] = $arParams['ASNAME'];
}
if($arResult['ALLOW_BBCODES'] == 0)
{
	$arResult['SHOW_FILEMAN'] = 0;
}
$arResult['A_NAME'] = '';
$arResult['EMAIL'] = '';
$arResult['TEXT'] = '';
$arResult['P_ID'] = -1;
$arResult['ERRORS'] = array();
$arResult['MESSAGES'] = array();
$arResult['CAN_MODIFY'] = $arParams['CAN_MODIFY'];


// if there is some property of element
// that disallow users to comment
if (strlen(COption::GetOptionString($MODULE_ID, 'restricted_property')) > 0)
{
	$restrictedProperty = COption::GetOptionString($MODULE_ID, 'restricted_property');
	$arFilter = array(
		"CODE" => $restrictedProperty
	);
	$rsProperty = CIBlockElement::GetProperty($iblockId, $arParams['OBJECT_ID'], array(), $arFilter);
	if ($arProperty = $rsProperty->Fetch())
	{
		if ($arProperty["VALUE"])
		{
			$arResult['COMMENT_NOT_ALLOWED'] = true;
			$arResult['CAN_COMMENT'] = "N";
		}
	}
}

if($arParams['CACHE_TYPE'] == "A" || $arParams['CACHE_TYPE'] == "Y")
{
	$cache_id = serialize(array($arParams['OBJECT_ID'], ($comment->IsModerator()?true:false), ($GLOBALS['USER']->IsAuthorized()?true:false)));
	$cache_path = "/".SITE_ID.'/'.str_replace(':', '/', $this->GetName())."/".$arParams["OBJECT_ID"];
}

if(!$USER->IsAuthorized())
{
	$arResult['CURRENT_USER'] = 0;
	if($arResult['NON_AUTHORIZED_USER_CAN_COMMENT'] == 'Y')
	{
		$arResult['CAN_COMMENT'] = 'Y';
		if ($arResult["CAPTCHA_TYPE"] == "CAPTCHA_BITRIX")
		{
			$arResult["CAPTCHA_CODE"] = htmlspecialchars($APPLICATION->CaptchaGetCode(), ENT_COMPAT | ENT_HTML401, SITE_CHARSET);
		}
		else if($arResult["CAPTCHA_TYPE"] == "ROBOT")
		{
			$arResult["CAPTCHA_ROBOT_CODE"] = CTreelikeComments::GenerateString(rand(5,15));
			SetCookie("gString", $arResult["CAPTCHA_ROBOT_CODE"], time()+3600*24, "/");
			$_SESSION["CAPTCHA_ROBOT_CODE"] = $arResult["CAPTCHA_ROBOT_CODE"];
		}
		else
		{
			$arResult["NO_CAPTCHA"] = "Y";
		}
	}
	else
	{
		$arResult['CAN_COMMENT'] = 'N';
	}
}
else
{
	$arResult['CURRENT_USER'] = $USER->GetID();
	$arResult['CAN_COMMENT'] = 'Y';
	$arResult['CAPTCHA_TYPE'] = 'NO';
}

if($_COOKIE['saveName'] && $arResult['CAPTCHA_TYPE'] != "CAPTCHA_BITRIX")
{
	$arResult['saveName'] = $_COOKIE['saveName'];
}

if($_SESSION['SCROLL_TO_COMMENT'])
{
	$arResult['SCROLL_TO_COMMENT'] = $_SESSION['SCROLL_TO_COMMENT'];
	$_SESSION['SCROLL_TO_COMMENT'] = 0;
}

if($_SESSION['TO_MODERATE'])
{
	$arResult['TO_MOD'] = 1;
	$arResult['MESSAGES'][] = GetMessage("TO_MODERATOR");
	$_SESSION['TO_MODERATE'] = 0;
}
else
{
	$arResult['TO_MOD'] = 0;
}


// add/modify comment @todo refactoring
if(isset($_POST['COMMENT']))
{

	// search ip address in black list
	$userIp = CTreelikeComments::GetIP();
	$ips = trim(COption::GetOptionString("prmedia.treelikecomments", "ban", ""));
	$arIPs = explode(",", $ips);
	foreach ($arIPs as $ip)
	{
		if ($userIp == trim($ip))
		{
			ShowError(GetMessage('BANNED_MESSAGE'));
			$arResult['ERRORS']['BANNED'] = true;
			break;
		}
	}


	if($_POST['PARENT_ID'] == 0)
		$_POST['PARENT_ID'] = false;

	if(strlen($_POST['COMMENT']))
	{
		$captchaError = false;

		if ($arResult['CAPTCHA_TYPE'] == 'ROBOT')
		{
			if ($_POST['ROBOT_STRING'] != $_COOKIE['gString'])
			{
				$captchaError = true;
				$arResult['ERRORS']['ROBOT'] = GetMessage('ROBOT_ERROR');
			}
			else if (isset($_POST['NEW_ROBOT_STRING']) && $_POST['NEW_ROBOT_STRING'] != 'success')
			{
				$captchaError = true;
				$arResult['ERRORS']['ROBOT'] = GetMessage('ROBOT_ERROR');
			}
		}
		if(($_POST["ROBOT_STRING"] != $_COOKIE["gString"]) && ($arResult["CAPTCHA_TYPE"] == "ROBOT"))
		{
			$captchaError = true;
			$arResult['ERRORS']['ROBOT'] = GetMessage('ROBOT_ERROR');
		}
		if (isset($_POST['comment_begin_time']))
		{
			$bt = intval($_POST['comment_begin_time']);
			$ct = time();
			$mt = intval($arParams['FORM_MIN_TIME']);
			$mt = $mt > 0 ? $mt : 3;
			if ($bt > $ct || $ct - $bt < $mt)
			{
				$captchaError = true;
				$arResult['ERRORS']['ROBOT'] = GetMessage('ROBOT_ERROR');
			}
		}

		if($captchaError)
		{
			$arResult['P_ID'] = ($_POST['PARENT_ID'] == 0) ? 0 : $_POST['PARENT_ID'];

			$arResult['A_NAME'] = $_POST['AUTHOR_NAME'];
			$arResult['EMAIL'] = $_POST['EMAIL'];
			$arResult['TEXT'] = $_POST['COMMENT'];

			$_SESSION['ISERROR_TOCACHE'] = true;
		}

		// check site charset
		$cs = SITE_CHARSET;
		$_POST['COMMENT'] = (!$cs) ? htmlspecialchars($_POST['COMMENT']) : htmlspecialchars($_POST['COMMENT'], ENT_NOQUOTES, $cs);

		$no_follow = ($arResult['NO_FOLLOW'] == 'Y') ? 'rel="nofollow"' : '';

		if(COption::GetOptionString($MODULE_ID, 'bb_code_enable'))
		{
			$_POST['COMMENT'] = CTreelikeComments::ParseText($_POST['COMMENT'],
				array(
					"NO_FOLLOW" => $no_follow,
					"SHOW_FILEMAN" => $arResult['SHOW_FILEMAN'])
				);
		}
		else
		{
			if($arResult['NO_FOLLOW'] == 'Y')
				$_POST['COMMENT'] = preg_replace("#(https?|ftp)://\S+[^\s.,>)\];'\"!?]#",'<a href="\\0" rel="nofollow">\\0</a>',
					$_POST['COMMENT']);
			else
				$_POST['COMMENT'] = preg_replace("#(https?|ftp)://\S+[^\s.,>)\];'\"!?]#",'<a href="\\0">\\0</a>',
					$_POST['COMMENT']);
		}
		if($arResult['CAN_COMMENT'] == 'Y')
		{
			if(isset($_POST["ROBOT"]))
				$arResult['ERRORS']['ROBOT'] = GetMessage('ROBOT_ERROR');

				if ($arResult['CAPTCHA_TYPE'] == 'CAPTCHA_BITRIX' && !$USER->IsAuthorized()
				&& !$APPLICATION->CaptchaCheckCode($_REQUEST["captcha_word"], $_REQUEST["captcha_sid"]))
				{
					$arResult['ERRORS']['CAPTCHA'] = GetMessage('WRONG_CAPTCHA');

					$arResult['P_ID'] = ($_POST['PARENT_ID'] == 0) ? 0 : $_POST['PARENT_ID'];

					$arResult['A_NAME'] = $_POST['AUTHOR_NAME'];
					$arResult['EMAIL'] = $_POST['EMAIL'];
					$arResult['TEXT'] = $_POST['COMMENT'];

					$_SESSION['ISERROR_TOCACHE'] = true;

					// @todo check this (clear cache for captcha)
					$uniq_str1 = serialize(array($arParams['OBJECT_ID'], true, false));
					$uniq_str2 = serialize(array($arParams['OBJECT_ID'], false, false));
					$uniq_str3 = serialize(array($arParams['OBJECT_ID'], false, true));
					$uniq_str4 = serialize(array($arParams['OBJECT_ID'], true, true));

					CPHPCache::Clean($uniq_str1, $cache_path);
					CPHPCache::Clean($uniq_str2, $cache_path);
					CPHPCache::Clean($uniq_str3, $cache_path);
					CPHPCache::Clean($uniq_str4, $cache_path);

				}
				else
				{
					$_POST['COMMENT'] = nl2br($_POST['COMMENT']);

					if(CTreelikeComments::StopWordExists($_POST['COMMENT']))
						$arResult['ERRORS']['STOP_WORD'] = GetMessage('STOP_WORD_EXIST');

					if($arResult['ALLOW_SMILES'] == 1)
						$_POST['COMMENT'] = TreelikeCommentsSetSmiles($_POST['COMMENT'],
							array(
								":)" => "smile.png",
								":D" => "xd.png",
								":(" => "sad.png",
								"x_x" => "x_x.png",
								"0_0" => "0_0.png",
							),
							array("FOLDER" => $templateFolder)
						);
				}
		}
		else
        {
            $arResult['ERRORS'][] = GetMessage('NOT_AUTHORIZED');
        }

		if(count($arResult["ERRORS"]) == 0)
		{
			SetCookie("gString", "", time()-3600, "/");
            SetCookie("gColor", "", time()-3600, "/");

			$arPreparedFields = array();

            if($arResult["PREMODERATION"] == "Y" && !$comment->IsModerator())
            {
                $arPreparedFields["ACTIVATED"] = 0;
                $_SESSION['TO_MODERATE'] = 1;
                $_SESSION['ISERROR_TOCACHE'] = true;
            }
            else
            {
                $arPreparedFields["ACTIVATED"] = 1;
            }

            if($comment->IsModerator())
				$arPreparedFields["ACTIVATED"] = 1;

			if($arResult["CAPTCHA_TYPE"] == "CAPTCHA_BITRIX" || !$USER->IsAuthorized())
			{
				$arPreparedFields['AUTHOR_NAME'] = htmlspecialchars($_POST['AUTHOR_NAME'], ENT_COMPAT | ENT_HTML401, SITE_CHARSET);
                $arPreparedFields['USER_ID'] = NULL;
			}
			else
			{
				$arPreparedFields['USER_ID'] = $USER->GetID();
                $arPreparedFields['AUTHOR_NAME'] = NULL;
			}

			$arPreparedFields['EMAIL'] = (!$_POST['EMAIL']) ? NULL : $_POST['EMAIL'];
			$arPreparedFields['COMMENT'] = trim($_POST["COMMENT"]);
			$arPreparedFields['PARENT_ID'] = $_POST['PARENT_ID'];

			$arComment = Array(
				"PARENT_ID"    => $arPreparedFields['PARENT_ID'],
				"OBJECT_ID" => $arParams["OBJECT_ID"],
				"COMMENT"=> $arPreparedFields['COMMENT'],
				"USER_ID"           => $arPreparedFields['USER_ID'],
				"AUTHOR_NAME"         => $arPreparedFields['AUTHOR_NAME'],
                "REMOTE_ADDR"         => CTreelikeComments::GetIP(),
                "EMAIL"         => $arPreparedFields['EMAIL'],
				"ACTIVATED"    => intval($arPreparedFields['ACTIVATED']),
                "SITE_ID" => SITE_ID
			);

			// if isset comment_id, than update existing comment
			$commentId = intval($_POST['update_comment_id']);
			if ($commentId > 0)
			{
				$rsComment = $comment->GetByID($commentId);
				if ($arCommentResult = $rsComment->Fetch())
				{
					// check rights for comment
					if ($arCommentResult['USER_ID'] == CUser::GetID())
					{

						// update comment
						$arComment = array(
							"COMMENT" => $arPreparedFields['COMMENT'],
							"DATE_MODIFY" => date("d-m-Y H:i:s")
						);
						if ($comment->Update($commentId, $arComment) !== false)
						{
							$_SESSION['SCROLL_TO_COMMENT'] = $commentId;

							LocalRedirect($_SERVER['REQUEST_URI']);
						}
					}
				}

				if($ex = $APPLICATION->GetException())
				{
					$arResult['ERRORS'][] = $ex->GetString();
				}
				else
				{
					$arResult['ERRORS'][] = GetMessage('UNKNOWN_ERROR');
				}
			}
			else
			{
				if ($NEW_ID = $comment->Add($arComment))
				{
					$NEW_ID = intval($NEW_ID);
					$_SESSION['COMMENTS']['ADD'] = 'Y';

					if ($arParams['CACHE_TYPE'] == "N")
					{
						SetCookie("saveName", $arPreparedFields['AUTHOR_NAME'], time() + 15552000, "/");
						$_COOKIE['saveName'] = $arPreparedFields['AUTHOR_NAME'];
					}

					// Set default values of emails

					$to_Mail_answer = "N";
					$to_Mail_like = "N";
					$to_Mail_admin = "N";

					// Get email. If "N" than means that nowhere to send

					if ($arResult['SEND_TO_USER_AFTER_ANSWERING'] == "Y")
						$to_Mail_answer = $comment->IsAnswered($NEW_ID);

					if ($arResult['SEND_TO_USER_AFTER_MENTION_NAME'] == "Y")
						$to_Mail_like = $comment->IsNameFound($NEW_ID);

					if ($arResult['SEND_TO_ADMIN_AFTER_ADDING'] == "Y")
						$to_Mail_admin = (COption::GetOptionString($MODULE_ID, 'admin_email')) ? COption::GetOptionString($MODULE_ID, 'admin_email') : "N";

					// Preparing general data

					if ($to_Mail_admin != "N" || $to_Mail_answer != "N" || $to_Mail_like != "N")
					{
						$mail_sender = COption::GetOptionString($MODULE_ID, 'mail_sender');
						$user_name = trim($comment->GetUserLogin($NEW_ID));

						$res = CIBlockElement::GetByID($arParams["OBJECT_ID"]); // GETTING DATA OF THE COMMENT WHICH WAS COMMENTED
						if ($ar_res = $res->GetNext())
						{
							$detail_url = $ar_res['DETAIL_PAGE_URL'];
							$element_name = $ar_res['NAME'];
							$element_code = $ar_res['CODE'];

							$detail_url = str_replace('%2F', '/', $detail_url);
							$element_name = str_replace("&quot;", '"', $element_name);
						}

						$rsSites = CSite::GetByID(SITE_ID);
						if ($arSite = $rsSites->Fetch())
							$siteName = $arSite["NAME"];
					}

					/**
					 * SEND TO USER AFTER ANSWERING
					 * */
					if ($arResult['SEND_TO_USER_AFTER_ANSWERING'] == "Y" && $arResult['PREMODERATION'] != 'Y' && $to_Mail_answer != 'N' && !($user_name == $comment->IsYours($NEW_ID, 'answer')))
					{
						$arEmailFieldsAnswering = array(
							"COMMENT_ID" => $NEW_ID,
							"COMMENT_TEXT" => $arPreparedFields['COMMENT'],
							"USER_ID" => intval($comment->GetUserID($NEW_ID)),
							"ELEMENT_ID" => $arParams["OBJECT_ID"],
							"ELEMENT_CODE" => $element_code,
							"ELEMENT_NAME" => $element_name,
							"DETAIL_PAGE_URL" => $detail_url,
							"USER_LOGIN" => $user_name,
							"PARENT_ID" => intval($arPreparedFields['PARENT_ID']),
							"EMAIL_FROM" => $mail_sender,
							"EMAIL_TO" => $to_Mail_answer,
							"SITE_NAME" => $siteName
						);

						CEvent::Send("PRMEDIA_TC_USER", SITE_ID, $arEmailFieldsAnswering, false);
					}

					/**
					 * !END SEND TO USER AFTER ANSWERING
					 * */
					/**
					 * SEND TO USER IF SOMEBODY MENTION HIS LOGIN
					 * */
					if ($arResult['SEND_TO_USER_AFTER_MENTION_NAME'] == "Y" && $arResult['PREMODERATION'] != 'Y' && !($user_name == $comment->IsYours($NEW_ID, 'like')))
					{
						$user_name_like = $user_name;

						$user_like_data = $comment->GetByMail($to_Mail_like);
						if ($arUser_like = $user_like_data->Fetch())
						{
							$user_id_like = $arUser_like['ID'];
							$user_login_like = $arUser_like['LOGIN'];
						}

						$arEmailFieldsLike = array(
							"USER_ID" => intval($user_id_like),
							"USER_LOGIN" => trim($user_login_like),
							"AUTHOR_ID" => intval($comment->GetUserID($NEW_ID)),
							"AUTHOR_LOGIN" => $user_name,
							"COMMENT_ID" => $NEW_ID,
							"COMMENT_TEXT" => $arPreparedFields['COMMENT'],
							"ELEMENT_ID" => $arParams["OBJECT_ID"],
							"ELEMENT_CODE" => $element_code,
							"ELEMENT_NAME" => $element_name,
							"DETAIL_PAGE_URL" => $detail_url,
							"EMAIL_FROM" => $mail_sender,
							"EMAIL_TO" => $to_Mail_like,
							"SITE_NAME" => $siteName
						);

						CEvent::Send("PRMEDIA_TC_USER_MENTION", SITE_ID, $arEmailFieldsLike, false);
					}

					/**
					 * !END SEND TO USER IF SOMEBODY MENTION HIS LOGIN
					 * */
					/**
					 * SEND TO ADMINISTRATOR
					 * */
					if ($arResult['SEND_TO_ADMIN_AFTER_ADDING'] == "Y" && $to_Mail_admin != 'N')
					{
						$arEmailFieldsAdmin = array(
							"COMMENT_ID" => $NEW_ID,
							"COMMENT_TEXT" => $arPreparedFields['COMMENT'],
							"USER_ID" => intval($comment->GetUserID($NEW_ID)),
							"ELEMENT_ID" => $arParams["OBJECT_ID"],
							"ELEMENT_CODE" => $element_code,
							"ELEMENT_NAME" => $element_name,
							"DETAIL_PAGE_URL" => $detail_url,
							"USER_LOGIN" => $user_name,
							"EMAIL_FROM" => $mail_sender,
							"EMAIL_TO" => $to_Mail_admin,
							"SITE_NAME" => $siteName
						);

						CEvent::Send("PRMEDIA_TC_ADMIN", SITE_ID, $arEmailFieldsAdmin, false);
					}

					/**
					 * !END SEND TO ADMINISTRATOR
					 * */
					$_POST['COMMENT'] = '';
					$_POST['AUTHOR_NAME'] = '';
					$_SESSION['SCROLL_TO_COMMENT'] = $NEW_ID;

					LocalRedirect($_SERVER['REQUEST_URI']);
				}
				else
				{
					if($ex = $APPLICATION->GetException())
					{
						$arResult['ERRORS'][] = $ex->GetString();
					}
					else
					{
						$arResult['ERRORS'][] = GetMessage('UNKNOWN_ERROR');
					}
				}
			}
		}

	}
	else
	{
		if(!strlen($_POST['COMMENT']))
		{
			$arResult['ERRORS'][] = GetMessage('COMMENT_NOT_FILLED');
		}
	}
}


// initialize comments array
$comments = array();
$left_margin = $arResult['LEFT_MARGIN'];
$max_depth_level = $arResult['MAX_DEPTH_LEVEL'];
$userlink = $arResult['TO_USERPAGE'];
$commentLink = $arResult["SHOW_COMMENT_LINK"];
if(!$commentLink)
{
	$commentLink = "N";
}


// returns comment list @todo refactoring
function getComments($PARENT_ID, $DEPTH_LEVEL, &$comments, &$left_margin, $date_format, $userlink, $commentLink, $max_depth_level, $asName, &$arIDs, $templateFolder, $canModify)
{
  $arFilter = array("OBJECT_ID_NUMBER" => OBJECT_ID, "PARENT_ID" => $PARENT_ID);
	$res = CTreelikeComments::GetList(array("ID" => "DESC"), $arFilter);

	while ($ob = $res->GetNext())
	{
		if ($ob['USER_ID'] != NULL)
		{
			$link = $userlink;

			if (preg_match('/USER_LOGIN/i', $link))
			{
				$userlogin_before = "#USER_LOGIN#";
				$userlogin_after = $ob['LOGIN'];

				$link = str_replace($userlogin_before, $userlogin_after, $link);
			}
			if (preg_match('/USER_ID/i', $link))
			{
				$id_before = "#USER_ID#";
				$id_after = $ob['USER_ID'];

				$link = str_replace($id_before, $id_after, $link);
			}
			if (preg_match('/PERSONAL_WWW/i', $link))
			{
				$id_before = "#PERSONAL_WWW#";
				$id_after = "";
				$arFilter = array(
					"ID" => $ob['USER_ID']
				);
				$arSelectParams = array(
					"FIELDS" => array(
						"ID", "PERSONAL_WWW"
					)
				);
				$rsUser = CUser::GetList($by = "ID", $order = "DESC", $arFilter, $arSelectParams);
				if ($arUser = $rsUser->Fetch())
				{
					$id_after = $arUser['PERSONAL_WWW'];
				}

				$link = str_replace($id_before, $id_after, $link);
			}
		}

		$user = array(
			"ID" => $ob['USER_ID'],
			"LOGIN" => $ob['LOGIN'],
			"NAME" => $ob['NAME'],
			"LAST_NAME" => $ob['LAST_NAME'],
			"PERSONAL_PHOTO" => CFile::GetPath($ob['PERSONAL_PHOTO']),
			"USERLINK" => $link
		);

		switch ($asName)
		{
			case "name_lastname":
				if ($user['NAME'] != "" && $user['LAST_NAME'] != "")
					$user['LOGIN'] = $user['NAME'] . " " . $user['LAST_NAME'];
				break;

			case "name":
				if ($user['NAME'] != "")
					$user['LOGIN'] = $user['NAME'];
				break;
		}

		if ($DEPTH_LEVEL > $max_depth_level - 1)
			$DEPTH_LEVEL = $max_depth_level - 1;

		if ($commentLink == "Y")
			$cLink = "comment_" . $ob["ID"];
		else
			$cLink = "N";

		$arIDs[] = $ob['ID'];

		// add modification date
		$commentText = $ob['~COMMENT'];
		$commentModify = "";

		if (!empty($ob['DATE_MODIFY']) && $canModify == "Y")
		{
			$commentModify = GetMessage('CHANGE_COMMENT_MSG');
			$commentDateTime = explode(" ", $ob['DATE_MODIFY']);
			$commentModify = str_replace("#DATE#", $commentDateTime[0], $commentModify);
			$commentModify = str_replace("#TIME#", $commentDateTime[1], $commentModify);
		}

		// if user is author add modification link
		$canEdit = "N";
		if (!empty($ob['USER_ID']) && $ob['USER_ID'] == CUser::GetID() && $canModify == "Y")
		{
			$canEdit = "Y";
		}

		$commentHiddenContent = TreelikeCommentsGetSmiles($ob['~COMMENT'],
			array(
				":)" => "smile.png",
				":D" => "xd.png",
				":(" => "sad.png",
				"x_x" => "x_x.png",
				"0_0" => "0_0.png",
			),
			array("FOLDER" => $templateFolder)
		);
		$commentHiddenContent = CTreelikeComments::ParseTextBack($commentHiddenContent);
		$commentHiddenContent = strip_tags($commentHiddenContent);


		$comments[$ob['ID']] = array(
			"ID" => $ob['ID'],
			"DEPTH_LEVEL" => $DEPTH_LEVEL,
			"PARENT_ID" => $ob['PARENT_ID'],
			"LEFT_MARGIN" => $DEPTH_LEVEL * $left_margin,
			"USER" => $user,
			"COMMENT_LINK" => $cLink,
			"COMMENT" => $commentText,
			"AUTHOR_NAME" => $ob['AUTHOR_NAME'],
			"DATE_CREATE" => FormatDate($date_format, strtotime($ob['NEW_DATE'])),
			"ACTIVATED" => $ob['ACTIVATED'],
			"VoteUp" => 0,
			"VoteDown" => 0,
			"TOTAL_VOTE" => 0,
			"COMMENT_HIDDEN_CONTENT" => $commentHiddenContent,
			"MODIFY_STRING" => $commentModify,
			"DATE_MODIFY" => $ob['DATE_MODIFY'],
			"CAN_EDIT" => $canEdit
		);

		getComments($ob['ID'], $DEPTH_LEVEL + 1, $comments, $left_margin, $date_format, $userlink, $commentLink, $max_depth_level, $asName, $arIDs, $templateFolder, $canModify);
	}

}



CJSCore::Init("jquery");

if ($this->StartResultCache(false, $cache_id, $cache_path))
{
	// caching
	$CACHE_MANAGER->StartTagCache($cache_path);
	$CACHE_MANAGER->RegisterTag("prmedia_treelike_comments_" . $arParams["OBJECT_ID"]);
	$arResult['GROUPS'] = $comment->IsModerator();
	getComments(false, 0, $comments, $left_margin, $arResult['DATE_FORMAT'], $userlink, $commentLink, $max_depth_level, $arResult['ASNAME'], $arIDs, $templateFolder, $arResult['CAN_MODIFY']);
	if (!empty($arIDs))
	{
		// @todo create method for below calculation in some class
		$resVoteIDs = $comment->GetVotedIDs($arIDs);
		while ($arIDs = $resVoteIDs->GetNext())
		{
			if ($arIDs["VOTE_TYPE"] == "UP")
			{
				if ($arIDs["COUNT"] > 0)
				{
					$comments[$arIDs["COMMENT_ID"]]["VoteUp"] = $arIDs["COUNT"];
				} else
				{
					$comments[$arIDs["COMMENT_ID"]]["VoteUp"] = 0;
				}
			}
			if ($arIDs["VOTE_TYPE"] == "DOWN")
			{
				if ($arIDs["COUNT"] > 0)
				{
					$comments[$arIDs["COMMENT_ID"]]["VoteDown"] = $arIDs["COUNT"];
				} else
				{
					$comments[$arIDs["COMMENT_ID"]]["VoteDown"] = 0;
				}
			}
			$comments[$arIDs["COMMENT_ID"]]["TOTAL_VOTE"] =
				(($comments[$arIDs["COMMENT_ID"]]["VoteDown"] * -1) + $comments[$arIDs["COMMENT_ID"]]["VoteUp"]);
		}
	}

	unset($arResult['ERRORS']['BANNED']);
	$arResult['COMMENTS'] = $comments;
	$arResult['COMMENTS_COUNT'] = sizeof($comment->GetActive($arParams["OBJECT_ID"]));
	$this->IncludeComponentTemplate();
}

//$tmp = 0;
if($_SESSION['ISERROR_TOCACHE'] || intval($arResult['SCROLL_TO_COMMENT'] && ($arParams['CACHE_TYPE'] == "A" || $arParams['CACHE_TYPE'] == "Y")) > 0)
{
	$CACHE_MANAGER->ClearByTag("prmedia_treelike_comments_".$arParams["OBJECT_ID"]);
	//$tmp = 1;
}

// @todo refactoring
// returns html text with img tags that replace appropriate smiles in text
function TreelikeCommentsSetSmiles($text = "", $iconArr = array(), $arParams = array())
{
	// Text must be filled
	if (strlen($text) == 0)
		return false;

	// Replace icons with pathnames
	foreach ($iconArr as $icon => $path):
		$text = str_replace($icon, '<img src="' . $arParams["FOLDER"] . '/images/icons/smiles/' . $path . '" />', $text);
	endforeach;

	return $text;
}

// returns html text with smiles that replace appropriate img tags in text
function TreelikeCommentsGetSmiles($text = "", $iconArr = array(), $arParams = array())
{
	// Text must be filled
	if (strlen($text) == 0)
		return false;

	// Replace icons with pathnames
	foreach ($iconArr as $icon => $path):
		$text = str_replace('<img src="' . $arParams["FOLDER"] . '/images/icons/smiles/' . $path . '" />', $icon, $text);
	endforeach;

	return $text;
}
?>