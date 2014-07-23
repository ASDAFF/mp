<?

IncludeModuleLangFile(__FILE__);

class CTreelikeComments
{

	function Add($arFields)
	{
		global $DB;

		$event_before = GetModuleEvents("prmedia.treelikecomments", "OnBeforePrmediaCommentAdd");

		while ($arEvent = $event_before->Fetch())
		{
			$executeResult = ExecuteModuleEvent($arEvent, $arFields);
			if ($executeResult === false)
			{
				return false;
			}
		}

		$arInsert = $DB->PrepareInsert("prmedia_treelike_comments", $arFields);
		$strSql = "INSERT INTO prmedia_treelike_comments (" . $arInsert[0] . ") VALUES (" . $arInsert[1] . ")";
		$DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);

		$event_after = GetModuleEvents("prmedia.treelikecomments", "OnAfterPrmediaCommentAdd");
		$lastID = intval($DB->LastID());

		$toEvent = $arFields;
		$toEvent['ID'] = $lastID;

		while ($arEvent = $event_after->Fetch())
			ExecuteModuleEvent($arEvent, $toEvent);

		return $lastID;
	}

	function Update($ID, $arFields)
	{
		global $DB;
		$ID = intval($ID);

		$event_before = GetModuleEvents("prmedia.treelikecomments", "OnBeforePrmediaCommentUpdate");

		while ($arEvent = $event_before->Fetch())
		{
			$executeResult = ExecuteModuleEvent($arEvent, $arFields);
			if ($executeResult === false)
			{
				return false;
			}
		}

		$db_element = $this->GetList(array(), array("ID" => $ID));

		if (!($ar_element = $db_element->Fetch()))
			return false;

		$strUpdate = $DB->PrepareUpdate("prmedia_treelike_comments", $arFields);
		$strSql = "UPDATE prmedia_treelike_comments SET " . $strUpdate . " WHERE ID = " . $ID;
		$DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);
		$Result = true;

		$event_after = GetModuleEvents("prmedia.treelikecomments", "OnAfterPrmediaCommentUpdate");

		$toEvent = $arFields;
		$toEvent['ID'] = $ID;

		while ($arEvent = $event_after->Fetch())
			ExecuteModuleEvent($arEvent, $toEvent);

		return $Result;
	}

	function Delete($ID, $hasChild = false)
	{
		global $USER;

		if (($this->IsModerator() || $USER->IsAdmin()) && intval($ID) > 0)
		{
			global $DB;
			$ID = intval($ID);
			$objectID = $this->GetObjectData($ID); // getting ID
			$objectID = intval($objectID);

			$event_before = GetModuleEvents("prmedia.treelikecomments", "OnBeforePrmediaCommentDelete");

			while ($arEvent = $event_before->Fetch())
			{
				$executeResult = ExecuteModuleEvent($arEvent, $ID);
				if ($executeResult === false)
				{
					return false;
				}
			}

			$arParentArr = array();

			$resParent = $DB->query("SELECT ID FROM prmedia_treelike_comments WHERE PARENT_ID = $ID AND OBJECT_ID = $objectID");
			if ($resParent->SelectedRowsCount() > 0)
			{
				while ($arParent = $resParent->fetch()):
					$arParentArr[] = array("ID" => $arParent['ID']);
				endwhile;

				$DB->Query("DELETE FROM prmedia_treelike_comments WHERE prmedia_treelike_comments.ID = $ID AND OBJECT_ID = $objectID", false, "File: " . __FILE__ . "<br>Line: " . __LINE__); // delete current

				foreach ($arParentArr as $oneParent):
					$this->Delete($oneParent['ID']);
				endforeach;
			}
			else
			{
				$DB->Query("DELETE FROM prmedia_treelike_comments WHERE prmedia_treelike_comments.ID = $ID AND OBJECT_ID = $objectID", false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
			}

			$event_after = GetModuleEvents("prmedia.treelikecomments", "OnAfterPrmediaCommentDelete");
			while ($arEvent = $event_after->Fetch())
				ExecuteModuleEvent($arEvent, $objectID);
		}
	}

	function Activate($ID)
	{
		global $USER;

		if (($this->IsModerator() || $USER->IsAdmin()) && intval($ID) > 0)
		{
			global $DB;
			$ID = intval($ID);

			$event_before = GetModuleEvents("prmedia.treelikecomments", "OnBeforePrmediaCommentActivate");

			while ($arEvent = $event_before->Fetch())
			{
				$executeResult = ExecuteModuleEvent($arEvent, $ID);
				if ($executeResult === false)
				{
					return false;
				}
			}

			$res = $DB->Query("UPDATE prmedia_treelike_comments SET ACTIVATED = 1 WHERE prmedia_treelike_comments.ID = " . $ID, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);

			$event_after = GetModuleEvents("prmedia.treelikecomments", "OnAfterPrmediaCommentActivate");
			while ($arEvent = $event_after->Fetch())
				ExecuteModuleEvent($arEvent, $ID);

			return $res;
		}
	}

	function Vote($arFields)
	{
		global $DB;
		global $USER;

		if ($USER->IsAuthorized())
		{
			$event = GetModuleEvents("prmedia.treelikecomments", "OnBeforePrmediaCommentVote");

			while ($arEvent = $event->Fetch())
			{
				$executeResult = ExecuteModuleEvent($arEvent, $arFields);
				if ($executeResult === false)
				{
					return;
				}
			}

			$arInsert = $DB->PrepareInsert("prmedia_treelike_comments_restrictions", $arFields);
			$strSql = "INSERT INTO prmedia_treelike_comments_restrictions (" . $arInsert[0] . ") VALUES (" . $arInsert[1] . ")";

			$DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
		}
	}

	function IsUserVoted($comment_id, $user_id)
	{
		global $DB;
		$comment_id = intval($comment_id);

		$query = $DB->query("SELECT USER_ID, COMMENT_ID FROM prmedia_treelike_comments_restrictions WHERE COMMENT_ID = $comment_id");

		while ($result = $query->GetNext())
		{
			if ($result['USER_ID'] == $user_id)
				return true;
		}

		return false;
	}

	function IsNameFound($comment_id)
	{
		global $DB;

		$comment_id = intval($comment_id);
		$user_list = array();
		$to_Mail = 'N';
		$comment_id = intval($comment_id);

		$rsUsers = CUser::GetList(($by = ""), ($order = ""), array());

		while ($myrow = $rsUsers->GetNext()) :
			$user_list[] = array("LOGIN" => $myrow['LOGIN'], "EMAIL" => $myrow['EMAIL']);
		endwhile;

		foreach ($user_list as $one):

			$val = $DB->ForSql($one['LOGIN']);

			$query = $DB->query("SELECT COMMENT
			FROM prmedia_treelike_comments
			WHERE prmedia_treelike_comments.ID = $comment_id
			AND (prmedia_treelike_comments.COMMENT LIKE '% " . $val . ",%'
			OR prmedia_treelike_comments.COMMENT LIKE '% " . $val . ".%'
			OR prmedia_treelike_comments.COMMENT LIKE '% " . $val . " %')");

			$result_text = $query->Fetch();

			if ($result_text)
				$to_Mail = $one['EMAIL'];

		endforeach;

		return $to_Mail;
	}

	function GetByMail($mail)
	{
		global $DB;
		$mail = trim($mail);

		$query_res = $DB->query("SELECT ID, LOGIN FROM b_user WHERE EMAIL = '$mail' LIMIT 0,1");

		return $query_res;
	}

	function AuthorMail($comment_id)
	{
		global $DB;
		$comment_id = intval($comment_id);
		$to_Mail = 'N';

		$query = $DB->query("SELECT USER_ID, EMAIL FROM prmedia_treelike_comments WHERE prmedia_treelike_comments.ID = $comment_id");
		$result = $query->fetch();

		if ($result)
		{
			if ($result['USER_ID'] == NULL && $result['EMAIL'] != NULL)
				$to_Mail = $result['EMAIL'];


			if ($result['USER_ID'] != NULL && $result['EMAIL'] == NULL)
			{
				$rsUser = CUser::GetByID($result['USER_ID']);
				$arUser = $rsUser->fetch();

				if ($arUser['EMAIL'] != NULL)
					$to_Mail = $arUser['EMAIL'];
			}
		}

		return $to_Mail;
	}

	function IsAnswered($comment_id)
	{
		global $DB;
		$comment_id = intval($comment_id);
		$to_Mail = 'N';

		$query_parent = $DB->query("SELECT PARENT_ID FROM prmedia_treelike_comments WHERE prmedia_treelike_comments.ID = $comment_id AND PARENT_ID IS NOT NULL");
		$result_parent = $query_parent->fetch();

		if ($result_parent)
		{
			$parent_ID = $result_parent['PARENT_ID'];

			$query_subs = $DB->query("SELECT USER_ID, EMAIL FROM prmedia_treelike_comments WHERE ID = $parent_ID");
			$result_subs = $query_subs->fetch();

			if ($result_subs['USER_ID'] == NULL && $result_subs['EMAIL'] != NULL)
				$to_Mail = $result_subs['EMAIL'];


			if ($result_subs['USER_ID'] != NULL && $result_subs['EMAIL'] == NULL)
			{
				$rsUser = CUser::GetByID($result_subs['USER_ID']);
				$arUser = $rsUser->fetch();

				if ($arUser['EMAIL'] != NULL)
					$to_Mail = $arUser['EMAIL'];
			}
		}

		return $to_Mail;
	}

	function IsYours($comment_id, $type = '')
	{
		global $DB;
		$comment_id = intval($comment_id);
		$user = 'N';

		if ($type = 'answer')
		{
			$query_parent = $DB->query("SELECT PARENT_ID FROM prmedia_treelike_comments WHERE prmedia_treelike_comments.ID = $comment_id AND PARENT_ID IS NOT NULL");
			$result_parent = $query_parent->fetch();

			if ($result_parent)
			{
				$parent_ID = $result_parent['PARENT_ID'];

				$query_subs = $DB->query("SELECT USER_ID, AUTHOR_NAME FROM prmedia_treelike_comments WHERE ID = $parent_ID");
				$result_subs = $query_subs->fetch();

				if ($result_subs['USER_ID'] != NULL && $result_subs['AUTHOR_NAME'] == NULL)
				{
					$rsUser = CUser::GetByID($result_subs['USER_ID']);
					$arUser = $rsUser->fetch();

					if ($arUser['LOGIN'] != NULL)
						$user = $arUser['LOGIN'];
				}
			}
		}

		if ($type = 'like')
		{

			$user_list = array();

			$rsUsers = CUser::GetList(($by = ""), ($order = ""), array());

			while ($myrow = $rsUsers->GetNext()) :
				$user_list[] = array("LOGIN" => $myrow['LOGIN'], "EMAIL" => $myrow['EMAIL']);
			endwhile;

			foreach ($user_list as $one):

				$val = $DB->ForSql($one['LOGIN']);

				$query = $DB->query("SELECT COMMENT
				FROM prmedia_treelike_comments
				WHERE prmedia_treelike_comments.ID = $comment_id AND prmedia_treelike_comments.COMMENT LIKE '%" . $val . "%'");
				$result_text = $query->fetch();

				if ($result_text)
					$user = $one['LOGIN'];

			endforeach;
		}

		return $user;
	}

	function GetActive($objectID)
	{
		global $DB;

		$comment_active_list = array();

		if ($objectID)
			$object = "AND OBJECT_ID = $objectID";

		$query = $DB->query("SELECT ID, PARENT_ID FROM prmedia_treelike_comments WHERE ACTIVATED = 1 $object");

		while ($result = $query->Fetch()):
			$comment_active_list[] = array("ID" => $result['ID'], "PARENT_ID" => $result['PARENT_ID']);
		endwhile;


		return $comment_active_list;
	}

	function GetFilterOperation($key)
	{
		$strNegative = "N";
		if (substr($key, 0, 1) == "!")
		{
			$key = substr($key, 1);
			$strNegative = "Y";
		}

		if (substr($key, 0, 2) == ">=")
		{
			$key = substr($key, 2);
			$strOperation = ">=";
		} elseif (substr($key, 0, 1) == ">")
		{
			$key = substr($key, 1);
			$strOperation = ">";
		} elseif (substr($key, 0, 2) == "<=")
		{
			$key = substr($key, 2);
			$strOperation = "<=";
		} elseif (substr($key, 0, 1) == "<")
		{
			$key = substr($key, 1);
			$strOperation = "<";
		} elseif (substr($key, 0, 1) == "@")
		{
			$key = substr($key, 1);
			$strOperation = "IN";
		} elseif (substr($key, 0, 1) == "%")
		{
			$key = substr($key, 1);
			$strOperation = "LIKE";
		} else
		{
			$strOperation = "=";
		}

		return array("FIELD" => $key, "NEGATIVE" => $strNegative, "OPERATION" => $strOperation);
	}

	function GetList($aSort = array(), $aFilter = array(), $limit = '', $count = false, $isFullList = false)
	{
		global $DB;

		$groupBy = " GROUP BY prmedia_treelike_comments.ID ";
		if ($aFilter['OBJECT_ID_UNIQUE'] === true)
		{
			unset($aFilter['OBJECT_ID_UNIQUE']);
			$groupBy = ' GROUP BY OBJECT_ID ';
		}

		if (intval($limit) > 0)
			$limit = " LIMIT 0, " . $limit;
		else
			$limit = "";

		$arHaving = array();

		foreach ($aFilter as $key => $val)
		{
			$val = $DB->ForSql($val);

			$key_res = CTreelikeComments::GetFilterOperation($key);
			$key = $key_res["FIELD"];
			$strNegative = $key_res["NEGATIVE"];
			$strOperation = $key_res["OPERATION"];

			if ($key == 'PARENT_ID')
			{
				if (intval($val) == 0)
					$arFilter[] = "prmedia_treelike_comments.PARENT_ID IS NULL";
				else
					$arFilter[] = "prmedia_treelike_comments.PARENT_ID=" . intval($val);

				continue;
			}

			if (strlen($val) <= 0)
				continue;
			switch (strtoupper($key))
			{
				case "ID":
					$arFilter[] = "prmedia_treelike_comments.ID = '" . $val . "'";
					break;
				case "PARENT_ID":
					$arFilter[] = "prmedia_treelike_comments.PARENT_ID = '" . $val . "'";
					break;

				case "OBJECT_ID":
					$arFilter[] = "b_iblock_element.NAME LIKE '%" . $val . "%'";
					break;

				case "IBLOCK_ID":
					$arFilter[] = "b_iblock_element.IBLOCK_ID IN (" . $val . ")";
					break;

				case "OBJECT_ID_NUMBER":
					$arFilter[] = "prmedia_treelike_comments.OBJECT_ID = '" . $val . "'";
					break;

				case "DATE":
					$arFilter[] = "DATE_FORMAT(prmedia_treelike_comments.DATE, '%d.%m.%Y') = '" . $val . "'";
					break;

				case "COMMENT":
					$arFilter[] = "prmedia_treelike_comments.COMMENT LIKE '%" . $val . "%'";
					break;

				case "USER_ID":
					$arFilter[] = "prmedia_treelike_comments.USER_ID='" . $val . "'";
					break;

				case "UP":
				case "DOWN":

					if ($strNegative == "Y")
						$strOperation = "!=";
					$arHaving[] = $key . " " . $strOperation . " " . $val;

					break;

				case "ACTIVATED":
					$arFilter[] = "ACTIVATED = '" . $val . "'";
					break;

				case "AUTHOR_NAME":
					$arFilter[] = "(prmedia_treelike_comments.AUTHOR_NAME LIKE '%" . $val . "%' OR b_user.LOGIN LIKE '%" . $val . "%' OR b_user.NAME LIKE '%" . $val . "%' OR b_user.LAST_NAME LIKE '%" . $val . "%')";
					break;

				case "IP":
					$arFilter[] = "prmedia_treelike_comments.REMOTE_ADDR = '" . $val . "'";
					break;

				case "EMAIL":
					$arFilter[] = "(prmedia_treelike_comments.EMAIL LIKE '%" . $val . "%' OR b_user.EMAIL LIKE '%" . $val . "%')";
					break;

				case "SITE_ID":
					$arFilter[] = "SITE_ID = '" . $val . "'";
					break;

				case "DATE_MODIFY":
					$arFilter[] = "DATE_FORMAT(prmedia_treelike_comments.DATE_MODIFY, '%d.%m.%Y') = '" . $val . "'";
					break;

				case "REMOTE_ADDR":
					$arFilter[] = "REMOTE_ADDR = '" . $val . "'";
					break;
			}
		}

		$arOrder = array();
		foreach ($aSort as $key => $val)
		{
			$ord = (strtoupper($val) <> "ASC" ? "DESC" : "ASC");
			switch (strtoupper($key))
			{

				case "ID":
					$arOrder[] = "prmedia_treelike_comments.ID " . $ord;
					break;
				case "PARENT_ID":
					$arOrder[] = "prmedia_treelike_comments.PARENT_ID " . $ord;
					break;

				case "OBJECT_ID":
					$arOrder[] = "b_iblock_element.NAME " . $ord;
					break;

				case "DATE":
					$arOrder[] = "prmedia_treelike_comments.DATE " . $ord;
					break;

				case "COMMENT":
					$arOrder[] = "prmedia_treelike_comments.COMMENT " . $ord;
					break;

				case "USER_ID":
					$arOrder[] = "prmedia_treelike_comments.USER_ID " . $ord;
					break;

				case "ACTIVATED":
					$arOrder[] = "prmedia_treelike_comments.ACTIVATED " . $ord;
					break;

				case "AUTHOR_NAME":
					$arOrder[] = "prmedia_treelike_comments.AUTHOR_NAME " . $ord;
					break;

				case "REMOTE_ADDR":
					$arOrder[] = "prmedia_treelike_comments.REMOTE_ADDR " . $ord;
					break;

				case "EMAIL":
					$arOrder[] = "prmedia_treelike_comments.EMAIL " . $ord;
					break;

				case "SITE_ID":
					$arOrder[] = "prmedia_treelike_comments.SITE_ID " . $ord;
					break;

				case "DATE_MODIFY":
					$arOrder[] = "prmedia_treelike_comments.DATE_MODIFY " . $ord;
					break;
			}
		}
		if (count($arOrder) == 0)
			$arOrder[] = "prmedia_treelike_comments.ID DESC";
		$sOrder = "\nORDER BY " . implode(", ", $arOrder);

		if (count($arFilter) == 0)
			$sFilter = "";
		else
			$sFilter = "\nWHERE " . implode("\nAND ", $arFilter);

		if (count($arHaving) == 0)
			$arHaving = "";
		else
			$arHaving = "\nHAVING " . implode("\nAND ", $arHaving);

		if (!$count)
		{

			if (!$isFullList)
			{
				$strSql = "
						SELECT
							prmedia_treelike_comments.ID, prmedia_treelike_comments.PARENT_ID, prmedia_treelike_comments.OBJECT_ID,
							DATE_FORMAT(prmedia_treelike_comments.DATE, '%d.%m.%Y %H:%i') AS NEW_DATE, prmedia_treelike_comments.COMMENT,
							DATE_FORMAT(prmedia_treelike_comments.DATE_MODIFY, '%d.%m.%Y %H:%i') AS DATE_MODIFY,
							prmedia_treelike_comments.USER_ID, prmedia_treelike_comments.ACTIVATED, prmedia_treelike_comments.AUTHOR_NAME,
							prmedia_treelike_comments.REMOTE_ADDR, prmedia_treelike_comments.SITE_ID, b_iblock_element.NAME, b_iblock_element.IBLOCK_ID,
							b_user.LOGIN, b_user.EMAIL, b_user.NAME, b_user.LAST_NAME, b_user.PERSONAL_PHOTO
						FROM
							prmedia_treelike_comments
						LEFT JOIN b_iblock_element ON OBJECT_ID = b_iblock_element.ID
						LEFT JOIN b_user ON USER_ID = b_user.ID

						" . $sFilter . " "
					. $groupBy
					. $arHaving
					. $sOrder
					. $limit;
			} else
			{
				$strSql = "
						SELECT
							prmedia_treelike_comments.ID, prmedia_treelike_comments.PARENT_ID, prmedia_treelike_comments.OBJECT_ID,
							DATE_FORMAT(prmedia_treelike_comments.DATE, '%d.%m.%Y %H:%i') AS NEW_DATE, prmedia_treelike_comments.COMMENT,
							DATE_FORMAT(prmedia_treelike_comments.DATE_MODIFY, '%d.%m.%Y %H:%i') AS DATE_MODIFY,
							prmedia_treelike_comments.USER_ID, prmedia_treelike_comments.ACTIVATED, prmedia_treelike_comments.AUTHOR_NAME,
							prmedia_treelike_comments.REMOTE_ADDR, prmedia_treelike_comments.SITE_ID, b_iblock_element.NAME, b_user.LOGIN,
							b_user.EMAIL, b_user.NAME, b_user.LAST_NAME, b_user.PERSONAL_PHOTO
						FROM
							prmedia_treelike_comments
						LEFT JOIN b_iblock_element ON OBJECT_ID = b_iblock_element.ID
						LEFT JOIN b_user ON USER_ID = b_user.ID
						" . $sFilter . " "
					. $groupBy
					. $arHaving
					. $sOrder
					. $limit;
			}

			return $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
		} else
		{
			$strSql = "
				SELECT
					COUNT(prmedia_treelike_comments.ID) as C
				FROM
					prmedia_treelike_comments LEFT JOIN b_iblock_element ON OBJECT_ID = b_iblock_element.ID LEFT JOIN b_user ON USER_ID = b_user.ID
				" . $sFilter . $sOrder . $limit;

			$res = $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);

			$res_cnt = $res->Fetch();
			return IntVal($res_cnt["C"]);
		}
	}

	function GetByID($ID)
	{
		$arFilter = array(
			"ID" => intval($ID)
		);

		$rs = $this->GetList(array(), $arFilter);
		return $rs;
	}

	function GetVotedIDs($arIDs = array())
	{
		global $DB;
		$strSql = "";

		$strSql = "SELECT COMMENT_ID, VOTE_TYPE, COUNT(ID) as COUNT
		FROM prmedia_treelike_comments_restrictions
		WHERE COMMENT_ID IN(" . implode(',', $arIDs) . ")
		GROUP BY COMMENT_ID, VOTE_TYPE";

		return $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
	}

	function IsModerator()
	{
		global $USER;

		if ($USER->IsAdmin())
			return true;

		$currentGroups = array();
		$currentGroups = $USER->GetUserGroupArray();
		$arGroups = explode(',', COption::GetOptionString('prmedia.treelikecomments', 'moderators'));

		$IsModerator = false;

		foreach ($currentGroups as $oneCurrent)
		{
			foreach ($arGroups as $oneModerator)
			{
				if ($oneModerator == $oneCurrent)
					$IsModerator = true;
			}
		}

		return $IsModerator;
	}

	function GetUserLogin($id_comment)
	{
		global $DB;
		$user_name = 'N';

		$query = $DB->query("SELECT USER_ID, AUTHOR_NAME FROM prmedia_treelike_comments WHERE ID = $id_comment");

		if ($result = $query->GetNext())
		{
			if ($result['USER_ID'] != NULL)
			{

				$res = CUser::GetByID($result['USER_ID']);
				$ar_res = $res->Fetch();

				$user_name = $ar_res['LOGIN'];
			} else
			{

				$user_name = $result['AUTHOR_NAME'];
			}
		}


		return $user_name;
	}

	function GetUserID($id_comment)
	{
		global $DB;
		$user_id = '';

		$query = $DB->query("SELECT USER_ID FROM prmedia_treelike_comments WHERE ID = $id_comment");

		if ($result = $query->GetNext())
		{
			if ($result['USER_ID'] != NULL)
				$user_id = $result['USER_ID'];
			else
				$user_id = GetMessage('NOT_AUTH');
		}

		return $user_id;
	}

	function GetObjectData($comment_id)
	{
		global $DB;
		$element_id = '';

		$query = $DB->query("SELECT OBJECT_ID FROM prmedia_treelike_comments WHERE prmedia_treelike_comments.ID = $comment_id");
		$result = $query->fetch();

		if ($result)
			$element_id = $result['OBJECT_ID'];

		return $element_id;
	}

	function GetCommentText($comment_id)
	{
		global $DB;
		$text = '';

		$query = $DB->query("SELECT COMMENT FROM prmedia_treelike_comments WHERE prmedia_treelike_comments.ID = $comment_id");
		$result = $query->fetch();

		if ($result)
			$text = $result['COMMENT'];


		return $text;
	}

	function getParentID($comment_id)
	{
		global $DB;
		$comment_id = intval($comment_id);

		$query = $DB->query("SELECT PARENT_ID FROM prmedia_treelike_comments WHERE prmedia_treelike_comments.ID = $comment_id");
		$result = $query->fetch();

		if ($result)
			$parentID = $result['PARENT_ID'];
		else
			$parentID = 0;

		return $parentID;
	}

	function getPopularElements($elementsCount)
	{
		global $DB;
		$elementsCount = intval($elementsCount);

		$strSql = "SELECT COUNT(ID) AS COMMENTS_COUNT, OBJECT_ID
		FROM prmedia_treelike_comments
		WHERE SITE_ID = '" . SITE_ID . "'
		GROUP BY OBJECT_ID
		ORDER BY COMMENTS_COUNT DESC
		LIMIT $elementsCount";

		return $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
	}

	// ---- from prmedia:treelike_comments component

	public static function ParseText($text = "", $arParams = array())
	{
		while (preg_match("#\[quote\](.*?)\[/quote\]#si", $text))
			$text = preg_replace("#\[quote\](.*?)\[/quote\]#si", '<div class="quote">\1</div>', $text);

		$text = preg_replace("#\[code\](.*?)\[/code\]#si", '<div class="code">\1</div>', $text);
		preg_match_all('#<div class="code">(.*?)</div>#si', $text, $code);

		$items = $code[0];

		$values = array();
		foreach($items as $key => $val)
			$values[] = "#$".$key."#";

		$text = str_replace($items, $values, $text);

		// Parse BB

		$search[] = "#\[b\](.*?)\[/b\]#si";
		$search[] = "#\[i\](.*?)\[/i\]#si";
		$search[] = "#\[s\](.*?)\[/s\]#si";
		$search[] = "#\[u\](.*?)\[/u\]#si";
		$search[] = "#\[IMG\](.*?)\[/IMG\]#si";

		$replace[] = '<strong>\1</strong>';
		$replace[] = '<i>\1</i>';
		$replace[] = '<strike>\1</strike>';
		$replace[] = '<u>\1</u>';
		$replace[] = '<div><img style="max-width:275px; max-height: 275px; padding: 5px 0 5px 0; clear: both;" src="\1"></div>';

		$text = preg_replace($search, $replace, $text);

		$text = preg_replace('#\[url=(https?|ftp)://(\S+[^\s.,>!?])\](.*?)\[\/url\]#si', '<a '.$arParams["NO_FOLLOW"].' href="http://$2">$3</a>', $text);

		// set link if there's no editor

		if($arParams["SHOW_FILEMAN"] == 0)
			$text = preg_replace("#(https?|ftp)://\S+[^\s.,>)\];'\"!?]#",'<a '.$arParams["NO_FOLLOW"].' href="\\0">\\0</a>',$text);

		$text = str_replace($values, $items, $text);

		return $text;
	}

	public static function ParseTextBack($text = "")
	{
		$arSearch = array(
			"/<strong>(.*?)<\/strong>/",
			"/<i>(.*?)<\/i>/",
			"/<u>(.*?)<\/u>/",
			"/<strike>(.*?)<\/strike>/",
			"/<div class=\"quote\">(.*?)<\/div>/",
			"/<div class=\"code\">(.*?)<\/div>/",
			"/<a (rel=\"nofollow\")? href=\"(.*?)\">(.*?)<\/a>/",
			"/<img style=\"max-width:275px; max-height: 275px; padding: 5px 0 5px 0; clear: both;\" src=\"(.*?)\">/"
		);

		$arReplace = array(
			"[b]$1[/b]",
			"[i]$1[/i]",
			"[u]$1[/u]",
			"[s]$1[/s]",
			"[quote]$1[/quote]",
			"[code]$1[/code]",
			"[url=$2]$3[/url]",
			"[IMG]$1[/IMG]"
		);

		$text = preg_replace($arSearch, $arReplace, $text);

		return $text;
	}

	public static function StopWordExists($text)
	{
		$existence = false;
		$wordString = COption::GetOptionString("prmedia.treelikecomments", "stop_words");

		if($wordString != "")
		{
			$wordString = str_replace(" ", "", $wordString);
			$wordArr = explode(",", $wordString);
			$regex = "/".implode("|", $wordArr)."/i";

			if(preg_match($regex, $text))
				$existence = true;
		}

		return $existence;
	}

	public static function GetIP()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];

		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

		else
			$ip = $_SERVER['REMOTE_ADDR'];

		return $ip;
	}

	public static function GenerateString($arParams = array())
	{
		$chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';

		$count_chars = strlen ($chars);

		for ($i = 0; $i < $arParams["LENGTH"]; $i++)
		{
			$rand = rand (1,$count_chars); // generating random figure from 1 to length of char string
			$string .= substr ($chars, $rand, 1); // returning string of 1 char
		}

		return $string;
	}
}