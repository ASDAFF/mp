<?

// prolog
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

// include required modules
if (!CModule::IncludeModule('prmedia.treelikecomments'))
{
	return false;
}

CTreelikeForumImporter::import();

?>









































































