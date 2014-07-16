<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Лайфхак");
?>

<?$APPLICATION->IncludeComponent('tesset:blog.list', '');?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>