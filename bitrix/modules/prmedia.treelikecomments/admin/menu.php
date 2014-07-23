<?

IncludeModuleLangFile(__FILE__);

if(CModule::IncludeModule("prmedia.treelikecomments"))
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/prmedia.treelikecomments/include.php");

    if(IsTreelikeModerator())
    {
        $aMenu = array(
    	"parent_menu" => "global_menu_services",
    	"section" => "prmedia_treelike_comments",
    	"sort" => 100,
    	"url" => "tc_comment_list.php",
        "more_url" => array("tc_comment_edit.php"),
    	"text" => GetMessage("M_TEXT"),
    	"title" => GetMessage("M_TITLE"),
    	"icon" => "prmedia_treelikecomments_menu_icon",
    	"page_icon" => "prmedia_treelikecomments_page_icon",
        "items_id" => "menu_prmedia_treelike_comments",
        "items" => array()
    );
    }
    
    
    return $aMenu;    
}

function IsTreelikeModerator()
{
    global $USER;
    
    if($USER->IsAdmin())
    	return true;
    
    $currentGroups = array();
    $currentGroups = $USER->GetUserGroupArray();
    $arGroups = explode(',', COption::GetOptionString('prmedia.treelikecomments', 'moderators'));
    
    $IsModerator = false;
    
    if(count($arGroups) > 0){
	    foreach($currentGroups as $oneCurrent)
	    {
	        foreach($arGroups as $oneModerator)
	        {
	            if($oneModerator == $oneCurrent) $IsModerator = true;
	        }
	    }	
    }
    
    return $IsModerator;

}

?>
