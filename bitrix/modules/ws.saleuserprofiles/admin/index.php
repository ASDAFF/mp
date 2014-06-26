<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ws.saleuserprofiles/include.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sale/prolog.php");

IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage("ws.saleuserprofiles_MENUITEMS_LIST"));

if($_REQUEST["mode"] == "list")
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");
else
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$adminPage->ShowSectionIndex("ws.saleuserprofiles_items", "sale");

if($_REQUEST["mode"] == "list")
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
else
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");

require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
?>