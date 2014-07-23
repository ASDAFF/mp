<?

IncludeModuleLangFile(__FILE__);

class CTreelikeForumImporter
{
	public static function import()
	{
		// include required modules
		if (!CModule::IncludeModule('forum'))
		{
			return false;
		}
		$moduleId = 'prmedia.treelikecomments';

		// retrieve or init session storage
		$sessionStorage = $_SESSION['tlc_import'];
		if ($_REQUEST['start'] == 'Y')
		{
			// get total count of comments
			$rsMessage = CForumMessage::GetList();
			$rsMessage->NavStart();
			$totalCount = $rsMessage->NavRecordCount;

			$sessionStorage = array(
				'processing_time' => time(),
				'current_count' => 0,
				'actual_count' => 0,
				'total_count' => $totalCount,
				'comment_id' => 1,
				'allow_smiles' => COption::GetOptionString($moduleId, 'smiles_enable') == 1 ? true : false,
				'allow_bb' => COption::GetOptionString($moduleId, 'bb_code_enable') == 1 ? true : false
			);
		}


		// import comments...
		$obComment = new CTreelikeComments;
		$is_finish = true;
		$currentTime = time();
		$arOrder = array(
			'ID' => 'ASC'
		);
		$arFilter = array(
			'>ID' => $sessionStorage['comment_id']
		);
		$rsMessage = CForumMessage::GetList($arOrder, $arFilter);
		while ($arMessage = $rsMessage->Fetch())
		{
			$is_finish = false;
			$sessionStorage['current_count']++;
			$sessionStorage['comment_id'] = $arMessage['ID'];

			$comment = $arMessage['POST_MESSAGE'];
			if ($sessionStorage['allow_smiles'])
			{
				$comment = CTreelikeForumImporter::setSmiles($comment);
			}
			if ($sessionStorage['allow_bb'])
			{
				$comment = CTreelikeForumImporter::setBB($comment);
			}


			$arFields = array(
				'REMOTE_ADDR' => $arMessage['AUTHOR_REAL_IP'],
				'AUTHOR_NAME' => $arMessage['AUTHOR_NAME'],
				'USER_ID' => $arMessage['AUTHOR_ID'],
				'OBJECT_ID' => $arMessage['PARAM2'],
				'COMMENT' => $comment,
				'DATE' => CDatabase::FormatDate($arMessage['POST_DATE'], "DD.MM.YYYY HH:MI:SS", "YYYY-MM-DD HH:MI:SS"),
				'ACTIVATED' => $arMessage['APPROVED'] == 'Y' ? 1 : 0
			);

			// counter
			if (!empty($arFields['USER_ID']))
			{
				$elementId = $obComment->Add($arFields);
				if (!empty($elementId))
				{
					if (empty($sessionStorage['first_element']))
					{
						$sessionStorage['first_element'] = $elementId;
					}
					$sessionStorage['actual_count']++;
					CTreelikeForumImporter::addImportedComment($elementId);
				}
			}

			// one step is one second
			if ($currentTime != time())
			{
				break;
			}
		}


		if ($is_finish)
		{
			// go to finish step
			echo '<script>document.getElementsByClassName("wizard-next-button")[0].click();</script>';
			CTreelikeForumImporter::clearCache();
			return;
		}

		// progress message
		$text = GetMessage('PRMEDIA_WIZARDS_IMPORT_FORUM_PROGRESS_MESSAGE');
		$arReplace = array(
			"#IMPORTED#" => $sessionStorage['current_count'],
			"#COUNT#" => $sessionStorage['total_count'],
			"#TIME#" => gmdate("i:s", time() - $sessionStorage['processing_time'])
		);
		$progressMessage = str_replace(array_keys($arReplace), $arReplace, $text);
		CAdminMessage::ShowMessage(array(
			"TYPE" => "PROGRESS",
			"MESSAGE" => GetMessage('PRMEDIA_SS_DIST_PROGRESS_TITLE'),
			"DETAILS" => $progressMessage,
			"HTML" => true,
			"PROGRESS_VALUE" => $sessionStorage['current_count'],
			"PROGRESS_TOTAL" => $sessionStorage['total_count']
		));
		echo "<script>jsPrmediaCommentImporter.update();</script>";

		$_SESSION['tlc_import'] = $sessionStorage;
	}

	public static function import_cancel()
	{
		$moduleId = 'prmedia.treelikecomments';

		// retrieve or init session storage
		$sessionStorage = $_SESSION['tlc_import_cancel'];
		if ($_REQUEST['start'] == 'Y')
		{
			$sessionStorage = array(
				'processing_time' => time(),
				'current_count' => 0,
				'total_count' => CTreelikeForumImporter::getImportedCount()
			);
		}


		// import cancel comments...
		$obComment = new CTreelikeComments;
		global $DB;
		$is_finish = true;
		$currentTime = time();
		$rsCommentId = $DB->Query('SELECT COMMENT_ID FROM prmedia_treelike_comments_import_forum');
		while ($arCommentId = $rsCommentId->Fetch())
		{
			$is_finish = false;
			$commentId = intval($arCommentId['COMMENT_ID']);
			$obComment->Delete($commentId);
			CTreelikeForumImporter::removeImportedComment($commentId);
			$sessionStorage['current_count']++;

			// one step is one second
			if ($currentTime != time())
			{
				break;
			}
		}


		if ($is_finish)
		{
			// go to finish step
			echo '<script>document.getElementsByClassName("wizard-next-button")[0].click();</script>';
			CTreelikeForumImporter::clearCache();
			return;
		}

		// progress message
		$text = GetMessage('PRMEDIA_WIZARDS_IMPORT_CANCEL_FORUM_PROGRESS_MESSAGE');
		$arReplace = array(
			"#IMPORTED#" => $sessionStorage['current_count'],
			"#COUNT#" => $sessionStorage['total_count'],
			"#TIME#" => gmdate("i:s", time() - $sessionStorage['processing_time'])
		);
		$progressMessage = str_replace(array_keys($arReplace), $arReplace, $text);
		CAdminMessage::ShowMessage(array(
			"TYPE" => "PROGRESS",
			"MESSAGE" => GetMessage('PRMEDIA_SS_DIST_CANCEL_PROGRESS_TITLE'),
			"DETAILS" => $progressMessage,
			"HTML" => true,
			"PROGRESS_VALUE" => $sessionStorage['current_count'],
			"PROGRESS_TOTAL" => $sessionStorage['total_count']
		));
		echo "<script>jsPrmediaCommentImporter.update();</script>";

		$_SESSION['tlc_import_cancel'] = $sessionStorage;
	}

	public static function addImportedComment($commentId)
	{
		$commentId = intval($commentId);
		if ($commentId <= 0)
		{
			return;
		}

		global $DB;
		$DB->Query("INSERT INTO prmedia_treelike_comments_import_forum (COMMENT_ID) VALUES($commentId)");
	}

	public static function removeImportedComment($commentId)
	{
		$commentId = intval($commentId);
		if ($commentId <= 0)
		{
			return;
		}

		global $DB;
		$DB->Query("DELETE FROM prmedia_treelike_comments_import_forum WHERE COMMENT_ID = $commentId");
	}

	public static function getImportedCount()
	{
		global $DB;
		$rsQuery = $DB->Query('SELECT COUNT(*) as CNT FROM prmedia_treelike_comments_import_forum');
		$arQuery = $rsQuery->Fetch();
		return intval($arQuery['CNT']);
	}

	public static function setSmiles($comment)
	{
		$iconsRoot = '/bitrix/components/prmedia/treelike_comments/templates/.default/images/icons/smiles';
		$arSmiles = array(
			":)" => "smile.png",
			":D" => "xd.png",
			":(" => "sad.png",
			"x_x" => "x_x.png",
			"0_0" => "0_0.png",
		);
		foreach($arSmiles as $key => $path)
		{
			$comment = str_replace("$key", "<img src='$iconsRoot/$path' alt='$key'>", $comment);
		}
		return $comment;
	}

	public static function setBB($comment)
	{
		while (preg_match("#\[quote\](.*?)\[/quote\]#si", $comment))
		{
			$comment = preg_replace("#\[quote\](.*?)\[/quote\]#si", '<div class="quote">\1</div>', $comment);
		}

		$comment = preg_replace("#\[code\](.*?)\[/code\]#si", '<div class="code">\1</div>', $comment);
		$code = false;
		preg_match_all('#<div class="code">(.*?)</div>#si', $comment, $code);

		$items = $code[0];

		$values = array();
		foreach($items as $key => $val)
		{
			$values[] = "#$$key#";
		}

		$comment = str_replace($items, $values, $comment);

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


		$comment = preg_replace('#\[url=(https?|ftp)://(\S+[^\s.,>!?])\](.*?)\[\/url\]#si', '<a rel="nofollow" href="http://$2">$3</a>', $comment);

		$comment = str_replace($values, $items, $comment);

		$comment = preg_replace($search, $replace, $comment);
		return $comment;
	}

	public static function clearCache()
	{
		if ($rsCacheDir = opendir($_SERVER['DOCUMENT_ROOT'] . '/bitrix/cache'))
		{
			while (false !== ($entry = readdir($rsCacheDir)))
			{
				BXClearCache(true, "/$entry/prmedia");
			}

			closedir($rsCacheDir);
		}
	}

	/**
	 * generates suffix that depends on count of elements
	 *
	 * @param mixed $values mixed params
	 * @param type $count count of elements for generating suffix
	 * @param type $base periodic parameter
	 * @return string|boolean return suffix depends on count of element
	 * 	if some condition has been satisfied or false, otherwise
	 */
	public static function getNumericSuffix($values, $count, $base = 10)
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
			} else
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
}