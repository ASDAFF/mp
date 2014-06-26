<?
$MODULE_ID = basename(dirname(dirname(__FILE__)));
CModule::IncludeModule($MODULE_ID);
IncludeModuleLangFile(__FILE_);

return array(
    "parent_menu" => "global_menu_store",
    "section" => $MODULE_ID,
    "sort" => 125,
    "text" => GetMessage("ws.saleuserprofiles_MODULE_NAME"),
    "title" => "",
    "url" => $MODULE_ID . "_" . "list.php",
    "icon" => "sale_menu_icon_buyers",
    "page_icon" => "",
    "items_id" => $MODULE_ID."_items",
    "more_url" => array("/bitrix/admin/ws.saleuserprofiles_edit.php"),
    "items" => array()
);
?>